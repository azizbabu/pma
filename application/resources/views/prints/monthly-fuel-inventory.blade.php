@extends('prints.master')

@section('title') Monthly Overall Fuel Inventroy Report @endsection 

@section('content')
@if(!empty($from_date) && !empty($to_date))
<h3 class="text-center">From:{{ Carbon::parse($from_date)->format('d-M-y') }} To:{{ Carbon::parse($to_date)->format('d-M-y') }}</h3>
<div class="margin-top-20">
    <table class="table table-bordered table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center" colspan="14"><h5>Inventory of HFO</h5></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class="text-center" colspan="14"><h5>{{ !empty($terminal_short_name) ? $terminal_short_name : '' }} Terminal:</h5></th>
            </tr>
            <tr>
                <th class="text-center align-middle" rowspan="3">Name of the Terminal</th>
                <th class="text-center align-middle" rowspan="3">Unit</th>
                <th class="text-center align-middle" rowspan="3">Opening Stock (A)</th>
                <th class="text-center align-middle" colspan="3">Mother Vessel Cargo Information</th>
                <th class="text-center align-middle" colspan="6">Delivered Quantity,MT</th>
                <th class="text-center align-middle" rowspan="3">Available Stock at MEB Site (K=(A+C)-J)</th>
                <th class="text-center align-middle" rowspan="3">Closing Stock (L=(A+C)-E)</th>
            </tr>
            <tr>
                <th class="text-center align-middle"  rowspan="2">Invoice Quantity (B) </th>
                <th class="text-center align-middle"  rowspan="2">Received Quantity (C)</th>
                <th class="text-center align-middle"  rowspan="2">Transportation Loss (D=(C-B)/B) %</th>
                <th class="text-center align-middle"  rowspan="2">Plant's Site (E)</th>
                <th class="text-center align-middle"  rowspan="2">Total Loan Given(F)</th>
                <th class="text-center align-middle"  rowspan="2">Total Loan Taken(G)</th>
                <th class="text-center align-middle" colspan="2">Loan Adjusted</th>
                <th class="text-center align-middle"  rowspan="2">Present Loan  Status (+/-) (J=(F-H)-(G-I))</th>
            </tr>
            <tr>
                <th class="text-center align-middle">Paid by Receiver(H)</th>
                <th class="text-center align-middle">Paid by Owner(I)</th>
            </tr>
            <tr>
                <td class="text-center align-middle"><span class="font-weight-bold">{{ !empty( $terminal) ? $terminal->name : '' }}</span></td>
                <td class="text-center align-middle">MT</td>
                <td class="text-center align-middle">{{ number_format($terminal_opening_stock, 3) }}</td>
                <td class="text-center align-middle">{{ !empty($motherVesselCarring) ? number_format($motherVesselCarring->invoice_quantity, 3) : '' }}</td>
                <td class="text-center align-middle">{{ !empty($motherVesselCarring) ? number_format($motherVesselCarring->received_quantity, 3) : '' }}</td>
                <td class="text-center align-middle">{{ !empty($motherVesselCarring) && $motherVesselCarring->invoice_quantity ? round((($motherVesselCarring->received_quantity - $motherVesselCarring->invoice_quantity)/$motherVesselCarring->invoice_quantity), 3) .'%' : '' }}</td>
                <td class="text-center align-middle">{{ number_format($plant_site_current_invoice_quantity, 3) }}</td>
                <td class="text-center align-middle">{{ number_format($fuelTrade->loan_given_qty, 3) }}</td>
                <td class="text-center align-middle">{{ number_format($fuelTrade->loan_receive_qty, 3) }}</td>
                <td class="text-center align-middle">{{ number_format($fuelTrade->loan_paid_by_party_qty, 3) }}</td>
                <td class="text-center align-middle">{{ number_format($fuelTrade->loan_return_qty, 3) }}</td>
                <td class="text-center align-middle">
                    {{ number_format($loan_status = (($fuelTrade->loan_given_qty - $fuelTrade->loan_paid_by_party)-($fuelTrade->loan_receive_qty-$fuelTrade->loan_return_qty)), 3) }}
                </td>
                <td class="text-center align-middle">{{ number_format($available_stock = ($terminal_opening_stock + $motherVesselCarring->received_quantity - $loan_status), 3) }}</td>
                <td class="text-center align-middle">
                    {{ number_format($terminal_closing_stock, 3) }}
                </td>
            </tr>
            <tr>
                <th colspan="14" class="text-center align-middle"><h5>Plant's Site</h5></th>
            </tr>
            <tr>
                <th class="text-center align-middle">Name of the Site</th>
                <th class="text-center align-middle">Unit</th>
                <th class="text-center align-middle">Opening Stock</th>
                <th class="text-center align-middle">Delivered Invoice Quantity from CTG Terminal</th>
                <th class="text-center align-middle">In Progress/Waiting</th>
                <th class="text-center align-middle">Received Quantity</th>
                <th class="text-center align-middle">Grand Total Quantity</th>
                <th class="text-center align-middle">Transportation Loss</th>
                <th class="text-center align-middle">Avarage Transportation Loss</th>
                <th class="text-center align-middle">Total Received at Plant(s) Site</th>
                <th class="text-center align-middle" colspan="2">Consumption</th>
                <th class="text-center align-middle" colspan="2">Closing Stock(Without In Progress)</th>
            </tr>
            @if($plants->isNotEmpty())
            @php 
            $i = 0;
            $total_plant_closing_stock = 0;
            @endphp 
            @foreach($plants as $plant)
            <tr>
                <td class="text-center align-middle">
                @php $fuel_quantity = $plant->getFuelQuantity($from_date, $to_date) @endphp
                    {{ $plant->name }}
                </td>
                <td class="text-center align-middle font-weight-bold">MT</td>
                <td class="text-center align-middle">{{ number_format($opening_stock = $plant->getOpeningStock($from_date), 3) }}</td>
                <td class="text-center align-middle">{{ number_format($fuel_quantity->invoice_quantity, 3) }}</td>
                <td class="text-center align-middle">
                    {{ number_format($fuel_quantity->waiting_quantity, 3) }}
                </td> 
                <td class="text-center align-middle">{{ number_format($fuel_quantity->received_quantity, 3) }}</td>
                <td class="text-center align-middle">
                    {{ number_format($grand_total_qty = ($opening_stock + $fuel_quantity->received_quantity + $fuel_quantity->waiting_qty) , 3) }}
                </td>
                <td class="text-center align-middle">
                    {{ $fuel_quantity->invoice_quantity && $fuel_quantity->received_quantity ? round((($fuel_quantity->received_quantity - ($fuel_quantity->invoice_quantity-$fuel_quantity->waiting_qty))/($fuel_quantity->invoice_quantity-$fuel_quantity->waiting_qty)),3) .'%' : 'NA' }}
                </td>
                @if($i == 0)
                    <td class="text-center align-middle" rowspan="3">{{ round($average_transportation_loss, 3) }}%</td>
                    <td class="text-center align-middle" rowspan="3">{{ number_format($plant_site_total_received_qty , 3) }}</td>
                @endif
                <td class="text-center align-middle" colspan="2">{{ number_format(($fuel_consumption = $plant->getFuelConsumption($from_date, $to_date)), 3) }}</td>
                <td class="text-center align-middle" colspan="2">
                    {{ number_format($plant_closing_stock = ($fuel_quantity->received_quantity - $fuel_consumption), 3) }}
                </td>
            </tr>
            @php 
            $i++;
            $total_plant_closing_stock += $plant_closing_stock;
            @endphp
            @endforeach
            @endif

            @php $fuelTypeCounter = 1 @endphp
            @foreach($fuelTypes as $fuelType)
                <tr>
                    <th class="text-center align-middle" colspan="14"><h6>Inventory of {{ $fuelType->name }}:</h6></th>
                </tr>

                @if($fuelTypeCounter == 1)
                <tr>
                    <th class="text-center align-middle">Name of the Site</th>
                    <th class="text-center align-middle">Unit</th>
                    <th class="text-center align-middle">Opening Stock (L)</th>
                    <th class="text-center align-middle">Invoice Quantity (L)</th>
                    <th class="text-center align-middle">Received Quantity (L)</th>
                    <th class="text-center align-middle" colspan="2">Available Stock (L)</th>
                    <th class="text-center align-middle" colspan="2">Transportation Loss</th>
                    <th class="text-center align-middle" colspan="3">Consumption</th>
                    <th class="text-center align-middle" colspan="2">Closing Stock</th>
                </tr>
                @endif

                @if(!empty($plants))
                    @foreach($plants as $plant)
                    <tr>
                        <th class="text-center align-middle">{{ $plant->name }}</th>
                        <th class="text-center align-middle">{{ $fuelType->unit }}</th>
                        <th class="text-center align-middle">
                            
                            {{ number_format($fuelInventoryOpeningStock = $plant->getFuelInventoryOpeningStock($fuelType->id, $from_date), 3) }}
                        </th>
                        <th class="text-center align-middle">
                            @php $fuelInventory = $plant->getMonthlyFuelInventoryInfo($fuelType->id, $from_date, $to_date); @endphp

                            {{ number_format($fuelInventory->invoice_quantity, 3) }}
                        </th>
                        <th class="text-center align-middle">
                            {{ number_format($fuelInventory->received_quantity, 3) }}
                        </th>
                        <th class="text-center align-middle" colspan="2">
                            {{ number_format($fuelInventoryAvailableStock = ($fuelInventoryOpeningStock + $fuelInventory->received_quantity), 3) }}
                        </th>
                        <th class="text-center align-middle" colspan="2">
                            {{ $fuelInventory->invoice_quantity && $fuelInventory->received_quantity ? round((($fuelInventory->received_quantity - $fuelInventory->invoice_quantity)/$fuelInventory->invoice_quantity * 100), 3) .'%' : 'NA' }}
                        </th>
                        <th class="text-center align-middle" colspan="3">
                            {{ number_format($fuelInventory->consumption, 3) }}
                        </th>
                        <th class="text-center align-middle" colspan="2">
                            {{ number_format(($fuelInventoryAvailableStock - $fuelInventory->consumption), 3) }} 
                        </th>
                    </tr>
                    @endforeach
                @endif
                @php $fuelTypeCounter++ @endphp
            @endforeach
                    
            <tr>
                <th class="text-center align-middle" colspan="14"><h4>Inland HFO Transportation Summary:</h4></th>
            </tr>
            <tr>
                <th class="text-center align-middle">Description</th>
                <th class="text-center align-middle">Unit</th>
                <th class="text-center align-middle">Inland Transportation Loss Quantity</th>
                <th class="text-center align-middle">Inland Transportation Loss (%)</th>
                <th class="text-center align-middle">Total Inland Floating or In Progress Quantity</th>
                <th class="text-center align-middle" colspan="2">Overall Transportation Loss (%) (Singapore to Plant)</th>
                <th class="text-center align-middle" colspan="3">Closing Stock of HFO at CTG MEB, Plants, Floating Vessel & Loan</th>
                <th class="text-center align-middle" colspan="4">Remarkes</th>
            </tr>
            <tr>
                <th class="text-center align-middle">Transportation Loss</th>
                <th class="text-center align-middle">MT</th>
                <th class="text-center align-middle">{{ round($inland_transportation_loss_qty, 3) }}</th>
                <th class="text-center align-middle">{{ round($average_transportation_loss, 3) }}%</th>
                <th class="text-center align-middle">{{ round($total_waiting_quantity, 3) }}</th>
                <th class="text-center align-middle" colspan="2">{{ number_format($overall_transportation_loss, 3) }}%</th>
                <th class="text-center align-middle" colspan="3">
                    {{ number_format(($terminal_closing_stock + $total_plant_closing_stock + $total_waiting_quantity - $fuelTrade->loan_given_qty + ( $fuelTrade->loan_receive_qty - $fuelTrade->loan_return_qty)), 3) }}
                </th>
                <th class="text-center align-middle" colspan="4">("-" figure represents losses)</th>
            </tr>
            <tr>
                <td colspan="14">&nbsp;</td>
            </tr>
        </tbody>
    </table>
</div>

{{--  
<div class="remarks margin-top-20">
    <h6 class="font-weight-bold"><u>Remarks</u></h6>
    <ol>
        <li>At Present Ststus Loan, (+) Figure Represents we gave Loan & (-) Figure Represents we received Loan.</li>
        <li>We Received HFO as Loan from Raj Lanka on March 2018 as Loan and Continued Remaining Balance upto April, 2018 was Total 1121.459 MT.</li>
        <li>We Gave Sinha Power Total 1093.786 MT HFO on April, 2018 as Loan.</li>
    </ol>
</div>
--}}
@endif
@endsection

@section('custom-style')

@endsection
