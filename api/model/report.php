<?php

require_once __DIR__ . '/../db/db.php';

/**
 * Hitung total penjualan (omset) dari transaksi cabang
 */
function get_total_income($branch_id)
{
    $sql = "SELECT COALESCE(SUM(total), 0) as total_income FROM tu_transaction WHERE branch_id = $1";
    $result = db_fetch_all($sql, [$branch_id]);
    return (int)$result[0]['total_income'];
}

/**
 * Hitung total modal dari aset yang terjual
 */
function get_total_cost_of_goods_sold($branch_id)
{
    $sql = "
        SELECT COALESCE(SUM(ta.quantity * ba.buy_price), 0) as total_cost
        FROM tu_asset_transaction ta
        JOIN tu_transaction t ON ta.transaction_id = t.tu_transaction_id
        JOIN tu_branch_asset ba ON ta.asset_id = ba.tu_branch_asset_id
        WHERE t.branch_id = $1
    ";
    $result = db_fetch_all($sql, [$branch_id]);
    return (int)$result[0]['total_cost'];
}

/**
 * Hitung keuntungan (total pendapatan - total modal)
 */
function get_total_profit($branch_id)
{
    $income = get_total_income($branch_id);
    $cost = get_total_cost_of_goods_sold($branch_id);
    return $income - $cost;
}

/**
 * Hitung pertumbuhan pendapatan dibandingkan periode sebelumnya
 * @param string $interval format PostgreSQL (e.g., '1 month', '1 week')
 */
function get_income_growth($branch_id, $interval_month = 1)
{
    $sql = "
        SELECT
            COALESCE(SUM(CASE WHEN createdAt >= NOW() - ($2 * INTERVAL '1 month') THEN total ELSE 0 END), 0) as current,
            COALESCE(SUM(CASE WHEN createdAt < NOW() - ($2 * INTERVAL '1 month')
                              AND createdAt >= NOW() - ($2 * 2 * INTERVAL '1 month') THEN total ELSE 0 END), 0) as previous
        FROM tu_transaction
        WHERE branch_id = $1
    ";

    $result = db_fetch_all($sql, [$branch_id, $interval_month]);

    $current = (int)$result[0]['current'];
    $previous = (int)$result[0]['previous'];

    if ($previous === 0) {
        return $current > 0 ? 100 : 0;
    }

    return round((($current - $previous) / $previous) * 100, 2);
}


/**
 * Rangkuman laporan keuangan cabang
 */
function get_financial_report_summary($branch_id)
{
    return [
        'total_income' => get_total_income($branch_id),
        'total_cost' => get_total_cost_of_goods_sold($branch_id),
        'total_profit' => get_total_profit($branch_id),
        'income_growth_percent' => get_income_growth($branch_id),
    ];
}

function get_monthly_income($branch_id, $year = null)
{
    if ($year === null) {
        $year = date('Y'); // default: tahun sekarang
    }

    $sql = "
        SELECT
            EXTRACT(MONTH FROM createdat) AS month,
            SUM(total) AS income
        FROM tu_transaction
        WHERE branch_id = $1 AND EXTRACT(YEAR FROM createdat) = $2
        GROUP BY month
        ORDER BY month
    ";

    $result = db_fetch_all($sql, [$branch_id, $year]);

    // Inisialisasi semua bulan ke 0
    $monthlyIncome = array_fill(1, 12, 0);
    foreach ($result as $row) {
        $month = (int) $row['month'];
        $monthlyIncome[$month] = (float) $row['income'];
    }

    return $monthlyIncome;
}
function get_asset_summary($token)
{
    $summary = [];

    // Total modal awal semua aset milik user
    $sql = "
        SELECT 
            SUM(ba.buy_price * ba.quantity) AS total_modal 
        FROM tu_branch_asset ba
        JOIN tu_user_branch ub ON ba.branch_id = ub.tu_user_branch_id
        JOIN tu_user u ON ub.user_id = u.tu_users_id
        WHERE u.tu_users_id = $1
    ";
    $modal = db_fetch_all($sql, [$token])[0];
    $summary['total_modal'] = $modal['total_modal'] ?? 0;

    // Distribusi modal ke setiap cabang
    $sql = "
        SELECT 
            ub.branch_name,
            SUM(ba.buy_price * ba.quantity) AS modal_per_branch
        FROM tu_branch_asset ba
        JOIN tu_user_branch ub ON ba.branch_id = ub.tu_user_branch_id
        JOIN tu_user u ON ub.user_id = u.tu_users_id
        WHERE u.tu_users_id = $1
        GROUP BY ub.branch_name
    ";
    $summary['modal_per_branch'] = db_fetch_all($sql, [$token]);

    // Sisa aset di semua cabang
    $sql = "
        SELECT 
            ba.name,
            ub.branch_name,
            ba.quantity AS sisa_qty
        FROM tu_branch_asset ba
        JOIN tu_user_branch ub ON ba.branch_id = ub.tu_user_branch_id
        JOIN tu_user u ON ub.user_id = u.tu_users_id
        WHERE u.tu_users_id = $1
        ORDER BY ba.name, ub.branch_name
    ";
    $summary['sisa_asset_per_branch'] = db_fetch_all($sql, [$token]);

    // Aset terjual per cabang
    $sql = "
        SELECT 
            ub.branch_name,
            ba.name,
            SUM(at.quantity) AS total_terjual,
            SUM(at.total) AS total_income
        FROM tu_asset_transaction at
        JOIN tu_branch_asset ba ON at.asset_id = ba.tu_branch_asset_id
        JOIN tu_transaction t ON at.transaction_id = t.tu_transaction_id
        JOIN tu_user_branch ub ON t.branch_id = ub.tu_user_branch_id
        JOIN tu_user u ON ub.user_id = u.tu_users_id
        WHERE u.tu_users_id = $1
        GROUP BY ub.branch_name, ba.name
        ORDER BY ub.branch_name, ba.name
    ";
    $summary['penjualan_per_branch'] = db_fetch_all($sql, [$token]);

    return $summary;
}
