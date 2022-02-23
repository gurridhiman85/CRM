<table>
        <tbody>
        <tr>
            <th width="14" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Contact ID</th>
            <th width="14" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">HH ID</th>
            <th width="14" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Date</th>
            <th width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: center;text-indent: 7px;">Amount</th>
            <th width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Class</th>
            <th width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Activity Cat 1</th>
            <th width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Activity Cat 2</th>
            <th width="35" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Activity</th>
            <th width="14" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Memo</th>
            <th width="14" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Account</th>
            <th width="35" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Client Message</th>
            <th width="35" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Customer</th>
        </tr>
        @foreach($records as $record)
            <tr>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{{ $record['DS_MKC_ContactID'] }}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{{ $record['DS_MKC_HouseholdID'] }}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{{ $record['Date'] }}</td>
                <td style="border: 1px solid #d0d0d0;text-align: right;text-indent: 8px;">{{ $record['Amount'] }}</td>
                <td style="border: 1px solid #d0d0d0;text-align: right;text-indent: 8px;">{{ $record['Class'] }}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{{ $record['Productcat1_Des'] }}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{{ $record['Productcat2_Des'] }}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{{ $record['Product'] }}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{{ $record['memo'] }}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{{ $record['Account'] }}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{{ $record['ClientMessage'] }}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{{ $record['customer'] }}</td>
            </tr>


        @endforeach
        </tbody>
    </table>

