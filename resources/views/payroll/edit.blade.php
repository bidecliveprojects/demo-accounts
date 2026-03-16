@php
    use App\Helpers\CommonHelper;
    $counter = 1;
@endphp
@extends('layouts.layouts')
<style>
    .table-responsive { position: relative; overflow: auto; }
    .table th, .table td { position: relative; z-index: 1; }
    .table th.sticky, .table td.sticky { position: sticky; left: 0; background: #fff; z-index: 2; }
</style>
@section('content')
    <div class="well_N">
        <div class="boking-wrp dp_sdw">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    {{ CommonHelper::displayPageTitle('Edit Payroll Detail') }}
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                    <a href="{{ route('payroll.show', ['id' => $epd->id]) }}" class="btn btn-info btn-xs">View</a>
                    <a href="{{ route('payroll.index') }}" class="btn btn-success btn-xs">+ View List</a>
                </div>
            </div>
            <div class="lineHeight">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p><strong>Month-Year:</strong> {{ $epd->month_year }}
                    &nbsp;|&nbsp;
                    <strong>Employee Type:</strong>
                    @if ($epd->employee_type_id == 1) Non Teaching Staff
                    @elseif ($epd->employee_type_id == 2) Teaching Staff
                    @elseif ($epd->employee_type_id == 3) Nazim
                    @elseif ($epd->employee_type_id == 4) Naib Nazim
                    @else Moavin
                    @endif
                    </p>
                </div>
            </div>
            <div class="lineHeight">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive wrapper">
                        <form method="POST" action="{{ route('payroll.update') }}">
                            @csrf
                            <input type="hidden" name="epd_id" value="{{ $epd->id }}" />
                            <table class="table table-responsive table-bordered" id="ExportParasList">
                                <thead>
                                    <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center sticky">Name / Emp No</th>
                                        <th class="text-center">Basic Salary</th>
                                        @foreach($normalAllowance as $naRow)
                                            <th class="text-center">{{ $naRow->allowance_name }}</th>
                                        @endforeach
                                        <th class="text-center">Total<br />Allowance</th>
                                        @foreach($additionalAllowance as $aaRow)
                                            <th class="text-center">{{ $aaRow->allowance_name }}</th>
                                        @endforeach
                                        <th class="text-center">Total Additional<br />Allowance</th>
                                        <th class="text-center">Gross Salary</th>
                                        @foreach($deductionType as $dtRow)
                                            <th class="text-center">{{ $dtRow->deduction_name }}</th>
                                        @endforeach
                                        <th class="text-center">Total<br />Deduction</th>
                                        <th class="text-center">Net Salary</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payrollRows as $row)
                                        @php
                                            $empId = $row->emp_id;
                                            $epddId = $row->id;
                                            $normalAmt = [];
                                            $additionalAmt = [];
                                            $deductionAmt = [];
                                            if (isset($allowanceDetails[$epddId])) {
                                                foreach ($allowanceDetails[$epddId] as $a) {
                                                    if ($a->allowance_type == 1) $normalAmt[$a->at_id] = $a->amount;
                                                    else $additionalAmt[$a->at_id] = $a->amount;
                                                }
                                            }
                                            if (isset($deductionDetails[$epddId])) {
                                                foreach ($deductionDetails[$epddId] as $d) {
                                                    $deductionAmt[$d->dt_id] = $d->amount;
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <input type="hidden" name="emp_array[]" value="{{ $empId }}" />
                                            <input type="hidden" name="epdd_id_{{ $empId }}" value="{{ $epddId }}" />
                                            <td class="text-center">{{ $counter++ }}</td>
                                            <td class="sticky">{{ $row->emp_name }} / {{ $row->emp_no }}</td>
                                            <td class="text-right">
                                                <input type="number" step="any" onchange="makeGrossSalary('{{ $empId }}')" name="emp_basic_salary_{{ $empId }}" id="emp_basic_salary_{{ $empId }}" value="{{ $row->basic_salary }}" class="form-control" />
                                            </td>
                                            @foreach($normalAllowance as $naRow)
                                                <td class="text-center" style="width:120px;">
                                                    <input type="hidden" name="emp_normal_allowance_{{ $empId }}[]" value="{{ $naRow->id }}" />
                                                    <input type="number" step="any" onchange="calculateAmounts(1,'normalAllowanceClass','{{ $empId }}')" name="normal_allowance_{{ $empId }}_{{ $naRow->id }}" id="normal_allowance_{{ $naRow->id }}_{{ $empId }}" value="{{ $normalAmt[$naRow->id] ?? 0 }}" class="normalAllowanceClass_{{ $empId }} form-control" />
                                                </td>
                                            @endforeach
                                            <td class="text-center">
                                                <input type="number" step="any" readonly name="emp_total_allowance_{{ $empId }}" id="emp_total_allowance_{{ $empId }}" value="{{ $row->total_allowance }}" class="form-control" />
                                            </td>
                                            @foreach($additionalAllowance as $aaRow)
                                                <td class="text-center" style="width:120px;">
                                                    <input type="hidden" name="emp_additional_allowance_{{ $empId }}[]" value="{{ $aaRow->id }}" />
                                                    <input type="number" step="any" onchange="calculateAmounts(2,'additionalAllowanceClass','{{ $empId }}')" name="additional_allowance_{{ $empId }}_{{ $aaRow->id }}" id="additional_allowance_{{ $aaRow->id }}_{{ $empId }}" value="{{ $additionalAmt[$aaRow->id] ?? 0 }}" class="additionalAllowanceClass_{{ $empId }} form-control" />
                                                </td>
                                            @endforeach
                                            <td class="text-center">
                                                <input type="number" step="any" readonly name="emp_total_additional_allowance_{{ $empId }}" id="emp_total_additional_allowance_{{ $empId }}" value="{{ $row->total_additional_allowance }}" class="form-control" />
                                            </td>
                                            <td class="text-center">
                                                <input type="number" step="any" readonly name="emp_gross_salary_{{ $empId }}" id="emp_gross_salary_{{ $empId }}" value="{{ $row->gross_salary }}" class="form-control" />
                                            </td>
                                            @foreach($deductionType as $dtRow)
                                                <td class="text-center" style="width:120px;">
                                                    <input type="hidden" name="emp_deduction_amount_{{ $empId }}[]" value="{{ $dtRow->id }}" />
                                                    <input type="number" step="any" onchange="calculateAmounts(3,'deductionClass','{{ $empId }}')" name="deduction_amount_{{ $empId }}_{{ $dtRow->id }}" id="deduction_amount_{{ $dtRow->id }}_{{ $empId }}" value="{{ $deductionAmt[$dtRow->id] ?? 0 }}" class="deductionClass_{{ $empId }} form-control" />
                                                </td>
                                            @endforeach
                                            <td class="text-center">
                                                <input type="number" step="any" readonly name="emp_total_deduction_{{ $empId }}" id="emp_total_deduction_{{ $empId }}" value="{{ $row->total_deduction }}" class="form-control" />
                                            </td>
                                            <td class="text-center">
                                                <input type="number" step="any" readonly name="emp_net_salary_{{ $empId }}" id="emp_net_salary_{{ $empId }}" value="{{ $row->net_salary }}" class="form-control" />
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="{{ 11 + count($normalAllowance) + count($additionalAllowance) + count($deductionType) }}" class="text-right">
                                            <button type="submit" class="btn btn-sm btn-success">Update Payroll</button>
                                            <a href="{{ route('payroll.index') }}" class="btn btn-sm btn-default">Cancel</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function calculateAmounts(type, className, empId) {
            var sum = 0;
            $('.' + className + '_' + empId).each(function() {
                var value = parseFloat(this.value) || 0;
                sum += value;
            });
            if (type == 1) {
                $('#emp_total_allowance_' + empId).val(sum);
            } else if (type == 2) {
                $('#emp_total_additional_allowance_' + empId).val(sum);
            } else if (type == 3) {
                $('#emp_total_deduction_' + empId).val(sum);
            }
            makeGrossSalary(empId);
        }

        function makeGrossSalary(empId) {
            var empBasicSalary = parseFloat($('#emp_basic_salary_' + empId).val()) || 0;
            var empTotalAllowance = parseFloat($('#emp_total_allowance_' + empId).val()) || 0;
            var empTotalAdditionalAllowance = parseFloat($('#emp_total_additional_allowance_' + empId).val()) || 0;
            var grossSalary = empBasicSalary + empTotalAllowance + empTotalAdditionalAllowance;
            $('#emp_gross_salary_' + empId).val(grossSalary.toFixed(2));
            makeNetSalary(empId);
        }

        function makeNetSalary(empId) {
            var empGrossSalary = parseFloat($('#emp_gross_salary_' + empId).val()) || 0;
            var empTotalDeduction = parseFloat($('#emp_total_deduction_' + empId).val()) || 0;
            var netSalary = empGrossSalary - empTotalDeduction;
            $('#emp_net_salary_' + empId).val(netSalary.toFixed(2));
        }
    </script>
@endsection
