<table class="table table-bordered table-hover color-table lkp-table">
    <thead>
    <tr>
        <th>Full Name</th>
        <th>January</th>
        <th>February</th>
        <th>March</th>
        <th>April</th>
        <th>May</th>
        <th>June</th>
        <th>July</th>
        <th>August</th>
        <th>September</th>
        <th>October</th>
        <th>November</th>
        <th>December</th>
        <th>Year</th>
    </tr>

    </thead>
    <tbody>
    @foreach($histories as $history)
        <tr>
            <td>{{ $history['User_FName'].' '.$history['User_LName'] }}</td>
            <td>{{ $history['January'] }}</td>
            <td>{{ $history['February'] }}</td>
            <td>{{ $history['March'] }}</td>
            <td>{{ $history['April'] }}</td>
            <td>{{ $history['May'] }}</td>
            <td>{{ $history['June'] }}</td>
            <td>{{ $history['July'] }}</td>
            <td>{{ $history['August'] }}</td>
            <td>{{ $history['September'] }}</td>
            <td>{{ $history['October'] }}</td>
            <td>{{ $history['November'] }}</td>
            <td>{{ $history['December'] }}</td>
            <td>{{ $history['Year'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
