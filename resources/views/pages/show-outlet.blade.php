@extends('layout')

@section('title')
    {{ $outlet->name }}
@endsection

@section('content')
    <a href="{{ route('outlets.home') }}">Back</a>
    <h1>{{ $outlet->name }}</h1>
    <p>Address :{{ $outlet->address }}</p>
    <p>Phone :{{ $outlet->phone }}</p>
    <p>Code :{{ $outlet->code }}</p>
    <p>Open Time :{{ $outlet->open_time }}</p>
    <p>Close Time :{{ $outlet->close_time }}</p>
    <br>
    <h2>Menu List</h2>
    <a href="{{ route('outlets.createMenu', $outlet->id) }}">
        <button>Create Menu</button>
    </a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Type</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        @foreach ($menus as $menu)
            <tr>
                <td>{{ $menu->id }}</td>
                <td>
                    <img src="{{ 'http://127.0.0.1:8000/' . $menu->image_url }}" width="100" height="100"
                        alt="{{ 'http://127.0.0.1:8000/' . $menu->image_url }}">
                </td>
                <td>{{ $menu->name }}</td>
                <td>{{ $menu->type }}</td>
                <td>{{ $menu->price }}</td>
                <td>
                    <form action="{{ route('outlets.deleteMenu', $menu->id) }}" method="post">
                        @csrf
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        @if (count($menus) == 0)
            <tr>
                <td colspan="6">No Menu</td>
            </tr>
        @endif
    </table>
    <br>
    <h2>Transaction Orders</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Customer Name</th>
            <th>Order</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->user->name }}</td>
                <td>
                    @foreach ($transaction->orders as $order)
                        {{ $order->menu->name }} X {{ $order->quantity }} =
                        {{ $order->menu->price * $order->quantity }}<br>
                    @endforeach
                </td>
                <td>{{ $transaction->total_price }}</td>
                <td>{{ $transaction->status }}</td>
                <td>
                    <form action="{{ route('outlets.updateTransaction', $transaction->id) }}" method="post">
                        @csrf
                        <button type="submit" @if ($transaction->status == 'paid') disabled @endif>update</button>
                    </form>
                </td>
            </tr>
        @endforeach
        @if (count($transactions) == 0)
            <tr>
                <td colspan="6">No Transaction</td>
            </tr>
        @endif
    </table>
@endSection
