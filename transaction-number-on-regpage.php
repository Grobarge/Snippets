<?php


add_filter(
    'FHEE_manage_event-espresso_page_espresso_registrations_columns',
    'my_filter_registration_list_table_columns',
    10,
    2
);
add_action(
    'AHEE__EE_Admin_List_Table__column_txnnumber__event-espresso_page_espresso_registrations',
    'my_registration_list_table_txnnumber_column',
    10,
    2
);
/**
 * this function adds the column name to the array of table headers
 *
 * @param array $columns
 * @param string $screen
 * @return array
 */
function my_filter_registration_list_table_columns($columns, $screen)
{
    if ($screen === "espresso_registrations_default") {
        $offset = isset($columns['TXN_paid']) ? 'TXN_paid' : '_REG_paid';
        $columns = EEH_Array::insert_into_array(
            $columns,
            array('txnnumber' => 'Transaction Number'),
            $offset,
            false
        );
    }
    return $columns;
}
/**
 * this function echoes out the data you want to appear in your custom column.
 *
 * @param \EE_Registration $item
 * @param string           $screen
 */
function my_registration_list_table_txnnumber_column($item, $screen)
{
    if ($screen === "espresso_registrations_default" && $item instanceof EE_Registration) {
        $txn = $item->transaction();
        if ($txn instanceof EE_Transaction) {
            $txn_payments = $txn->approved_payments();
            foreach ($txn_payments as $txn_payment) {
                if ($txn_payment instanceof EE_Payment) {
                    echo $txn_payment->txn_id_chq_nmbr() . '<br>';
                }
            }
        }
    }
}
