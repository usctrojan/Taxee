<?php if (!defined('CDN_URL')) exit('No direct script access allowed');
/**
 *
 */
class FederalTaxCalculatorModel extends TaxCalculatorModel {

    public function get_federal_data($year) {
        $response = array();
        if (!in_array($year, $this->supported_years)) {
            $response['success'] = false;
            $response['reason'] = "Invalid year";
        } else {
            $handle = file_get_contents(CDN_URL . "tax_tables/" . $year . "/federal.json", "r");
            $response['success'] = true;
            $tax_table = json_decode($handle);
            $response['data'] = $tax_table;
        }

        return $response;
    }

    public function calculate($year, $pay_rate, $pay_periods, $filing_status, $state) {
        $response['success'] = true;
        $data['fica']['amount'] = $this->get_fica_tax_amount($pay_rate, $pay_periods, $filing_status);
        $data['federal']['amount'] = $this->get_federal_income_tax_amount($year, $pay_rate, $pay_periods, $filing_status);

        $response['data'] = $data;

        return $response;
    }

    /**
     * Gets an employee's federal income tax amount
     *
     * @return federal_income_tax_amount - float
     *
     */
    public function get_federal_income_tax_amount($year, $pay_rate, $pay_periods, $filing_status) {
        $tax_table = $this->get_federal_data($year);
        $tax_table = $tax_table["data"];
        $fit_filing_status = $filing_status;
        $income = $pay_rate * $pay_periods;
        $payschedule = "annual";

        $target_table = $tax_table->tax_withholding_percentage_method_tables->$payschedule->$fit_filing_status;

        $adjusted_income = 0;
        $deduction_amount = 0;
        if (isset($target_table->deductions)) {
            foreach ($target_table->deductions as $deduction) {
                $deduction_amount += $deduction->deduction_amount;
            }
        }
        $exemption_amount = 0;
        if (isset($target_table->exemptions)) {
            //$exemptions = $target_table->exemptions->personal;
        }
        $adjusted_income = $income - $deduction_amount - $exemption_amount;

        $amount = 0;
        for ($mridx = 0; $mridx < sizeof($target_table->income_tax_brackets); $mridx++) {
            $tax_bracket = $target_table->income_tax_brackets[$mridx];

            if ($mridx == (sizeof($target_table->income_tax_brackets) - 1)) {
                $amount+= ($adjusted_income - $tax_bracket->bracket) * ($tax_bracket->marginal_rate / 100);
            } else if (($adjusted_income < $target_table->income_tax_brackets[$mridx + 1]->bracket)) {
                $amount += ($adjusted_income - $tax_bracket->bracket) * ($tax_bracket->marginal_rate / 100);
                break;
            } else {
                $amount += ($target_table->income_tax_brackets[$mridx + 1]->bracket - $tax_bracket->bracket) * ($tax_bracket->marginal_rate / 100);
            }
        }

        if ($amount < 0) $amount = 0;

        return (float) number_format($amount, 2, '.', '');
    }

    public function get_fica_tax_amount($pay_rate, $pay_periods, $is_married) {
        $amount = (($pay_rate * $pay_periods) * $this->ssa_rate) + (($pay_rate * $pay_periods) * $this->medicare_rate);

        return round($amount, 2);
    }
}
