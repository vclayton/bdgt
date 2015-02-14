@extends('app')

@section('breadcrumbs.items')
	<li><a href="/transactions">Transactions</a></li>
@endsection

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th></th>
							<th>Date</th>
							<th>Account</th>
							<th>Category</th>
							<th>Payee</th>
							<th>Inflow</th>
							<th>Outflow</th>
							<th>Cleared</th>
						</tr>
					</thead>
					<tbody>
					@foreach ($ledger->transactions() as $transaction)
						<tr>
							<td>
								<i class="fa fa-flag-o" style="color: {{ $transaction->flair }}"></i>
							</td>
							<td>
								<span class="editable" data-pk="{{ $transaction->id }}" data-url="/transactions/{{ $transaction->id }}" data-name="date">
									{{ $transaction->date }}
								</span>
							</td>
							<td>
								<span class="editable" data-pk="{{ $transaction->id }}" data-url="/transactions/{{ $transaction->id }}" data-name="account_name">
									{{ $transaction->account->name }}
								</span>
							</td>
							<td>
								<span class="editable" data-pk="{{ $transaction->id }}" data-url="/transactions/{{ $transaction->id }}" data-name="category_label">
									{{ $transaction->category->label }}
								</span>
							</td>
							<td>
								<span class="editable" data-pk="{{ $transaction->id }}" data-url="/transactions/{{ $transaction->id }}" data-name="payee">
									{{ $transaction->payee }}
								</span>
							</td>
							<td>
								@if ($transaction->inflow)
									$ <span class="editable" data-pk="{{ $transaction->id }}" data-url="/transactions/{{ $transaction->id }}" data-name="amount">
										{{ number_format($transaction->amount, 2) }}
									</span>
								@endif
							</td>
							<td>
								@if (!$transaction->inflow)
									$ <span class="editable" data-pk="{{ $transaction->id }}" data-url="/transactions/{{ $transaction->id }}" data-name="amount">
										{{ number_format($transaction->amount, 2) }}
									</span>
								@endif
							</td>
							<td>
								@if ($transaction->cleared)
									<i class="fa fa-check"></i>
								@endif
							</td>
						</tr>
					@endforeach
					<tfoot>
						<tr>
							<td colspan="5"><b>Total</b></td>
							<td><b>$ {{ number_format($ledger->totalInflow(), 2) }}</b></td>
							<td><b>$ {{ number_format($ledger->totalOutflow(), 2) }}</b></td>
							<td></td>
						</tr>
					</tfoot>
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('scripts-ready')
	$.fn.editableform.buttons =
	  '<button type="submit" class="btn btn-primary editable-submit btn-sm"><i class="fa fa-check"></i></button>' +
	  '<button type="button" class="btn btn-default editable-cancel btn-sm"><i class="fa fa-remove"></i></button>';

	$.fn.editable.defaults.mode = 'inline';

	$("table").DataTable({order: [[1, "desc"]]});

	$("table").editable({
		emptytext: '',
		selector: '.editable',
		ajaxOptions: {
			'method': 'PUT'
		},
		params: {
			'_token': '{{ csrf_token() }}'
		},
		//toggle: 'manual'
	});

	$(".edit-transaction").on('click', function(e) {
		$(this).closest('tr').editable('toggle');
	});
@endsection