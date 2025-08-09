<?php
// dashboard.php
require_once __DIR__ . '/../db/db.php';

/**
 * Hitung jumlah cabang usaha yang dimiliki user
 */
function get_total_branches($token)
{
    $sql = "
        SELECT COUNT(*) AS total_cabang
        FROM tu_user_branch
        WHERE user_id = $1
    ";
    $result = db_fetch_all($sql, [$token]);
    return (int)($result[0]['total_cabang'] ?? 0);
}

/**
 * Hitung total modal awal dari semua aset milik user
 */
function get_total_modal($token)
{
    $sql = "
        SELECT COALESCE(SUM(ba.buy_price * ba.quantity), 0) AS total_modal
        FROM tu_branch_asset ba
        JOIN tu_user_branch ub ON ba.branch_id = ub.tu_user_branch_id
        WHERE ub.user_id = $1
    ";
    $result = db_fetch_all($sql, [$token]);
    return (int)($result[0]['total_modal'] ?? 0);
}

/**
 * Hitung total transaksi (omset) dari semua cabang milik user
 */
function get_total_transactions($token)
{
    $sql = "
        SELECT COALESCE(SUM(t.total), 0) AS total_transaksi
        FROM tu_transaction t
        JOIN tu_user_branch ub ON t.branch_id = ub.tu_user_branch_id
        WHERE ub.user_id = $1
    ";
    $result = db_fetch_all($sql, [$token]);
    return (int)($result[0]['total_transaksi'] ?? 0);
}

/**
 * Hitung total keuntungan (omset - modal terjual)
 */
function get_total_profit_all_branches($user_id)
{
    $sql = "
        WITH income AS (
            SELECT COALESCE(SUM(t.total), 0) AS total_income
            FROM tu_transaction t
            JOIN tu_user_branch ub ON t.branch_id = ub.tu_user_branch_id
            WHERE ub.user_id = $1
        ),
        cost AS (
            SELECT COALESCE(SUM(at.quantity * ba.buy_price), 0) AS total_cost
            FROM tu_transaction t
            JOIN tu_asset_transaction at ON t.tu_transaction_id = at.transaction_id
            JOIN tu_branch_asset ba ON at.asset_id = ba.tu_branch_asset_id
            JOIN tu_user_branch ub ON t.branch_id = ub.tu_user_branch_id
            WHERE ub.user_id = $1
        )
        SELECT income.total_income, cost.total_cost
        FROM income, cost
    ";

    $result = db_fetch_all($sql, [$user_id]);
    $income = (int)($result[0]['total_income'] ?? 0);
    $cost = (int)($result[0]['total_cost'] ?? 0);

    return $income - $cost;
}


/**
 * Ambil ringkasan asset per branch
 */
function get_asset_overview($token)
{
    $sql = "
        SELECT 
            ub.branch_name,
            COUNT(ba.tu_branch_asset_id) AS total_item,
            SUM(ba.quantity) AS total_qty
        FROM tu_branch_asset ba
        JOIN tu_user_branch ub ON ba.branch_id = ub.tu_user_branch_id
        WHERE ub.user_id = $1
        GROUP BY ub.branch_name
        ORDER BY ub.branch_name
    ";
    return db_fetch_all($sql, [$token]);
}

/**
 * Ambil data ringkasan dashboard home
 */
function get_dashboard_summary($token)
{
    return [
        'total_cabang'      => get_total_branches($token),
        'total_modal'       => get_total_modal($token),
        'total_transaksi'   => get_total_transactions($token),
        'total_profit'      => get_total_profit_all_branches($token),
        'asset_overview'    => get_asset_overview($token),
    ];
}
