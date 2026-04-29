@php
    $summaries = [
        ['icon' => 'fa-clock-o', 'title' => 'Pending payments', 'count' => $pendingPayment->total_count ?? 0, 'amount' => number_format($pendingPayment->total_amount ?? 0, 2), 'color' => '#d97706'],
        ['icon' => 'fa-check-circle', 'title' => 'Approved payments', 'count' => $approvedPayment->total_count ?? 0, 'amount' => number_format($approvedPayment->total_amount ?? 0, 2), 'color' => '#15803d'],
        ['icon' => 'fa-bell-o', 'title' => 'Pending receipts', 'count' => $pendingReceipt->total_count ?? 0, 'amount' => number_format($pendingReceipt->total_amount ?? 0, 2), 'color' => '#ea580c'],
        ['icon' => 'fa-thumbs-o-up', 'title' => 'Approved receipts', 'count' => $approvedReceipt->total_count ?? 0, 'amount' => number_format($approvedReceipt->total_amount ?? 0, 2), 'color' => '#1d4ed8'],
        ['icon' => 'fa-cubes', 'title' => 'Purchase invoices', 'count' => $purchaseSummary->total_count ?? 0, 'amount' => number_format($purchaseSummary->total_amount ?? 0, 2), 'color' => '#0e7490', 'remaining' => number_format($purchaseSummary->total_remaining ?? 0, 2)],
        ['icon' => 'fa-line-chart', 'title' => 'Sales invoices', 'count' => $salesSummary->total_count ?? 0, 'amount' => number_format($salesSummary->total_amount ?? 0, 2), 'color' => '#6d28d9', 'remaining' => number_format($salesSummary->total_remaining ?? 0, 2)],
    ];
    $netClass = ($netProfit ?? 0) >= 0 ? 'text-success' : 'text-danger';
    $grossClass = ($grossProfit ?? 0) >= 0 ? 'text-success' : 'text-danger';
@endphp

<div class="dashboard-ajax-root">
    <div class="dashboard-period-banner">
        <div class="dashboard-period-banner-inner">
            <span class="dashboard-period-label"><i class="fa fa-calendar" aria-hidden="true"></i> Selected period</span>
            <span class="dashboard-period-dates">{{ date('d M Y', strtotime($fromDate)) }} — {{ date('d M Y', strtotime($toDate)) }}</span>
        </div>
    </div>

    <h2 class="dashboard-section-heading dashboard-section-heading-first">
        <span class="dashboard-section-icon"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
        Operations &amp; invoices
    </h2>
    <p class="dashboard-section-sub text-muted">Counts and amounts for the selected filters (PKR).</p>

    <div class="row dashboard-kpi-row">
        @foreach ($summaries as $summary)
            <div class="col-md-4 col-sm-6 col-xs-12 dashboard-kpi-col">
                <div class="dashboard-kpi-card" style="--dash-accent: {{ $summary['color'] }}">
                    <div class="dashboard-kpi-top">
                        <span class="dashboard-kpi-icon"><i class="fa {{ $summary['icon'] }}" aria-hidden="true"></i></span>
                        <h3 class="dashboard-kpi-title">{{ $summary['title'] }}</h3>
                    </div>
                    <div class="dashboard-kpi-meta">
                        <span class="dashboard-kpi-count">{{ $summary['count'] }}</span>
                        <span class="dashboard-kpi-count-label">records</span>
                    </div>
                    <div class="dashboard-kpi-amount">{{ $summary['amount'] }}</div>
                    @isset($summary['remaining'])
                        <div class="dashboard-kpi-remaining text-muted">
                            Outstanding / remaining: <strong>{{ $summary['remaining'] }}</strong>
                        </div>
                    @endisset
                </div>
            </div>
        @endforeach
    </div>

    <h2 class="dashboard-section-heading">
        <span class="dashboard-section-icon"><i class="fa fa-pie-chart" aria-hidden="true"></i></span>
        Profit &amp; loss summary
    </h2>

    <div class="row dashboard-pl-row">
        <div class="col-md-3 col-sm-6 col-xs-12 dashboard-pl-col">
            <div class="dashboard-pl-box">
                <span class="dashboard-pl-label">Total revenue</span>
                <span class="dashboard-pl-value text-primary">{{ number_format($sumRevenue, 2) }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 dashboard-pl-col">
            <div class="dashboard-pl-box">
                <span class="dashboard-pl-label">Total expense</span>
                <span class="dashboard-pl-value text-danger">{{ number_format($sumExpense, 2) }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 dashboard-pl-col">
            <div class="dashboard-pl-box">
                <span class="dashboard-pl-label">Gross profit</span>
                <span class="dashboard-pl-value {{ $grossClass }}">{{ number_format($grossProfit, 2) }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 dashboard-pl-col">
            <div class="dashboard-pl-box dashboard-pl-box-highlight">
                <span class="dashboard-pl-label">Net profit</span>
                <span class="dashboard-pl-value {{ $netClass }}">{{ number_format($netProfit, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="row dashboard-tables-row">
        <div class="col-md-6 col-xs-12 dashboard-table-col">
            <div class="panel panel-default dashboard-panel">
                <div class="panel-heading dashboard-panel-heading">
                    <strong><i class="fa fa-arrow-circle-down text-danger" aria-hidden="true"></i> Expenses by account</strong>
                    <span class="label label-danger dashboard-panel-badge">Total {{ number_format($sumExpense ?? 0, 2) }}</span>
                </div>
                <div class="panel-body dashboard-panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-condensed dashboard-mini-table dashboard-table-no-margin">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 44px;">#</th>
                                    <th>Account</th>
                                    <th class="text-right">Amount (PKR)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($expenses as $index => $exp)
                                    <tr>
                                        <td class="text-center text-muted">{{ $index + 1 }}</td>
                                        <td>{{ $exp->name }}</td>
                                        <td class="text-right text-danger">{{ number_format($exp->total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted dashboard-empty-cell">No expense lines in this period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xs-12 dashboard-table-col">
            <div class="panel panel-default dashboard-panel">
                <div class="panel-heading dashboard-panel-heading">
                    <strong><i class="fa fa-arrow-circle-up text-success" aria-hidden="true"></i> Revenue by account</strong>
                    <span class="label label-success dashboard-panel-badge">Total {{ number_format($sumRevenue ?? 0, 2) }}</span>
                </div>
                <div class="panel-body dashboard-panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-condensed dashboard-mini-table dashboard-table-no-margin">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 44px;">#</th>
                                    <th>Account</th>
                                    <th class="text-right">Amount (PKR)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($revenues as $index => $rev)
                                    <tr>
                                        <td class="text-center text-muted">{{ $index + 1 }}</td>
                                        <td>{{ $rev->name }}</td>
                                        <td class="text-right text-success">{{ number_format($rev->total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted dashboard-empty-cell">No revenue lines in this period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
