@if($section == 'contact')
    <table>
        <tbody>
        <tr>
            <td width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-indent: 1px;color: #357EC7;font-weight: 500;">Category</td>
            <td width="35" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-indent: 1px;color: #357EC7;font-weight: 500;">
                Field
            </td>
            <td width="75" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-indent: 1px;color: #357EC7;font-weight: 500;">
                Value
            </td>
        </tr>

        <tr>
            <td rowspan="19" width="25" style="text-indent:1px;vertical-align:center;color: #357EC7;font-weight: 500;">Name and Address</td>
            <td width="35" style="text-indent:1px;color: #357EC7;">
                Extended Name
            </td>
            <td width="75" style="text-align: left;text-indent:1px;">
                {{ $record['Extendedname'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">
                Gender
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['gender'] }}
            </td>
        </tr>

        <tr>

            <td style="text-indent:1px;color: #357EC7;">
                Email - Primary
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['Email'] }}
            </td>
        </tr>

        <tr>

            <td style="text-indent:1px;color: #357EC7;">
                Email - Primary - Status
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['email_status'] }}
            </td>
        </tr>

        <tr>

            <td style="text-indent:1px;color: #357EC7;">
                Email - Secondary
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['Email2'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">
                Email - Secondary - Status
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['email2_status'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">
                Email - Optout Reason
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['email_optout_reason'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">
                Phone - Primary
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['phone'].' ' .$record['phone_type'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">
                Phone - Secondary
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['Phone2'].' ' .$record['Phone2_type'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">Address</td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['Address'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">City</td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['City'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">State</td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['State'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">Zip</td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['Zip'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">Country</td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['Country'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">Company Name</td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['Company'] }}
            </td>
        </tr>

        <tr>

            <td style="text-indent:1px;color: #357EC7;">
                Job Title
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['JobTitle'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">Address Quality</td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['AddressQuality'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">Direct Mail Optout</td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['opt_mail'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">Suppression</td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['Suppression'] }}
            </td>
        </tr>

        <tr>
            <td rowspan="6" style="text-indent:1px;vertical-align:center;color: #357EC7;font-weight: 500;">Segment</td>
            <td style="text-indent:1px;color: #357EC7;">
                ZSS Segment
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['ZSS_Segment'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">
                Donor Segment
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['DonorSegment'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">
                Email Segment
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['EmailSegment'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">Lifecyle Segment</td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['LifecycleSegment'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">
                Member Segment
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['MemberSegment'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">
                Event Segment
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['EventSegment'] }}
            </td>
        </tr>

        <tr>
            <td rowspan="5" style="text-indent:1px;vertical-align:center;color: #357EC7;
    font-weight: 500;">Last Touch</td>
            <td style="text-indent:1px;color: #357EC7;">
                Status
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['TouchStatus'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">
                Channel
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['TouchChannel'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">
                Campaign
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['TouchCampaign'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">
                Date
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['TouchDate'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">
                Comments
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['TouchNotes'] }}
            </td>
        </tr>



        <tr>
            <td rowspan="5" style="text-indent:1px;vertical-align:center;color: #357EC7;
    font-weight: 500;">Key Events</td>
            <td style="text-indent:1px;color: #357EC7;">
                First Activity Date
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['firstDate'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">
                Last Activity Date
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['lastDate'] }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;">
                First Sesshin Date
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['FirstSesshinDate'] != '1900-01-01' ? $record['FirstSesshinDate'] : ''  }}
            </td>
        </tr>


        <tr>
            <td style="text-indent:1px;color: #357EC7;">
                Jukai Date
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['Jukai_Date'] != '1900-01-01' ? $record['Jukai_Date'] : '' }}
            </td>
        </tr>


        <tr>
            <td style="text-indent:1px;color: #357EC7;">Ordainment Date</td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['Ordainment_Date'] != '1900-01-01' ? $record['Ordainment_Date'] : ''  }}
            </td>
        </tr>

        <tr>
            <td style="text-indent:1px;color: #357EC7;
    font-weight: 500;">Notes</td>
            <td style="text-indent:1px;color: #357EC7;">Additional Information</td>
            <td style="text-indent:1px;">
                {{ $record['Notes'] }}
            </td>
        </tr>

        <tr>
            <td rowspan="3" style="text-indent:1px;vertical-align:center;color: #357EC7;
    font-weight: 500;">Contact IDs and Source</td>
            <td style="text-indent:1px;color: #357EC7;">
                Contact ID
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['DS_MKC_ContactID'] }}
            </td>
        </tr>


        <tr>
            <td style="text-indent:1px;color: #357EC7;">
                Household ID
            </td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['ds_mkc_householdid'] }}
            </td>
        </tr>


        <tr>
            <td style="text-indent:1px;color: #357EC7;">Prioritized Source Feed</td>
            <td style="text-align: left;text-indent:1px;">
                {{ $record['ds_mkc_source_feed'] }}
            </td>
        </tr>

        </tbody>
    </table>


@elseif($section == 'summary')
    <style>
        .total-cell{
            background-color:#f4f4f4;
        }
    </style>
    <table>

        <tbody>
            <tr>
                <td width="35" style="border: 1px solid #d0d0d0;text-indent: 1px;background-color:#e1eeff;font-weight: 500;text-align: left">Metric</td>
                <td style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: right;text-indent: 1px; direction: rtl;" width="20">Lifetime</td>
                <td style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: right;text-indent: 1px; direction: rtl;" width="20">Last 5 Years</td>
                <td style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: right;text-indent: 1px; direction: rtl;" width="20">Last 6 Months</td>
                <td style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: right;text-indent: 1px; direction: rtl;" width="20">Current Year</td>
                <td style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: right;text-indent: 1px; direction: rtl;" width="20">Prior Year 1</td>
                <td style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: right;text-indent: 1px; direction: rtl;" width="20">Prior Year 2</td>
                <td style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: right;text-indent: 1px; direction: rtl;" width="20">Prior Year 3</td>
                <td style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: right;text-indent: 1px; direction: rtl;" width="20">Prior Year 4</td>
            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px;background-color:#f4f4f4;color:#357EC7; font-weight:500;">Total Spend Amount</td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Life2date_SpendAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_5Yrs_SpendAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_6Mth_SpendAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['CurrentYr_SpendAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr1_SpendAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr2_SpendAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr3_SpendAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr4_SpendAmt'] !!}
                </td>

            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 2px;color:#357EC7;">Gift $</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Life2date_GiftsAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_5Yrs_GiftsAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_6Mth_GiftsAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['CurrentYr_GiftsAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr1_GiftsAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr2_GiftsAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr3_GiftsAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr4_GiftsAmt'] !!}
                </td>

            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 2px;color:#357EC7;">Membership $</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Life2date_MembrAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_5Yrs_MembrAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_6Mth_MembrAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['CurrentYr_MembrAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr1_MembrAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr2_MembrAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr3_MembrAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr4_MembrAmt'] !!}
                </td>

            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 2px;color:#357EC7; ">Event $</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Life2date_EventAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_5Yrs_EventAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_6Mth_EventAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['CurrentYr_EventAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr1_EventAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr2_EventAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr3_EventAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr4_EventAmt'] !!}
                </td>

            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 2px;color:#357EC7; ">Retail $</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Life2date_RtailAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_5Yrs_RtailAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_6Mth_RtailAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['CurrentYr_RtailAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr1_RtailAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr2_RtailAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr3_RtailAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr4_RtailAmt'] !!}
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 2px;color:#357EC7;">Rental $</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Life2date_RentlAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_5Yrs_RentlAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_6Mth_RentlAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['CurrentYr_RentlAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr1_RentlAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr2_RentlAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr3_RentlAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr4_RentlAmt'] !!}
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 2px;color:#357EC7; ">Miscellaneous $</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Life2date_MisclAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_5Yrs_MisclAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_6Mth_MisclAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['CurrentYr_MisclAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr1_MisclAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr2_MisclAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr3_MisclAmt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr4_MisclAmt'] !!}
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px;background-color:#f4f4f4;color:#357EC7; font-weight:500;">Total Activities</td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Life2date_NActivty'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_5Yrs_NActivty'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_6Mth_NActivty'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['CurrentYr_NActivty'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr1_NActivty'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr2_NActivty'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr3_NActivty'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr4_NActivty'] !!}
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px;background-color:#f4f4f4;color:#357EC7; font-weight:500;">Total Paid Events Attended</td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Life2date_NPaidEvt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_5Yrs_NPaidEvt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_6Mth_NPaidEvt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['CurrentYr_NPaidEvt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr1_NPaidEvt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr2_NPaidEvt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr3_NPaidEvt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr4_NPaidEvt'] !!}
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 2px;color:#357EC7;">Kessei</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Life2date_NKessei_'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_5Yrs_NKessei_'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_6Mth_NKessei_'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['CurrentYr_NKessei_'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr1_NKessei_'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr2_NKessei_'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr3_NKessei_'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr4_NKessei_'] !!}</td>

            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 2px;color:#357EC7;">ZSS Sesshins</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Life2date_NZSS_Ses'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_5Yrs_NZSS_Ses'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_6Mth_NZSS_Ses'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['CurrentYr_NZSS_Ses'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr1_NZSS_Ses'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr2_NZSS_Ses'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr3_NZSS_Ses'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr4_NZSS_Ses'] !!}</td>

            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 2px;color:#357EC7;">Open Sesshins</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Life2date_NOpn_Ses'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_5Yrs_NOpn_Ses'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_6Mth_NOpn_Ses'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['CurrentYr_NOpn_Ses'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr1_NOpn_Ses'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr2_NOpn_Ses'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr3_NOpn_Ses'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr4_NOpn_Ses'] !!}</td>

            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 2px;color:#357EC7;">Intro to Zen</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Life2date_NITZ_Wkd'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_5Yrs_NITZ_Wkd'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_6Mth_NITZ_Wkd'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['CurrentYr_NITZ_Wkd'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr1_NITZ_Wkd'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr2_NITZ_Wkd'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr3_NITZ_Wkd'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr4_NITZ_Wkd'] !!}</td>

            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 2px;color:#357EC7;">All-Day Sits</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Life2date_NAll_Day'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_5Yrs_NAll_Day'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_6Mth_NAll_Day'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['CurrentYr_NAll_Day'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr1_NAll_Day'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr2_NAll_Day'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr3_NAll_Day'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr4_NAll_Day'] !!}</td>

            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 2px;color:#357EC7;">Zazenkai</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Life2date_NSitting'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_5Yrs_NSitting'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_6Mth_NSitting'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['CurrentYr_NSitting'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr1_NSitting'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr2_NSitting'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr3_NSitting'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr4_NSitting'] !!}</td>

            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 2px;color:#357EC7;">ZSS Programs</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Life2date_NZSS_Pgm'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_5Yrs_NZSS_Pgm'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_6Mth_NZSS_Pgm'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['CurrentYr_NZSS_Pgm'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr1_NZSS_Pgm'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr2_NZSS_Pgm'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr3_NZSS_Pgm'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr4_NZSS_Pgm'] !!}</td>

            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 2px;color:#357EC7;">Open Programs</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Life2date_NOpn_Pgm'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_5Yrs_NOpn_Pgm'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_6Mth_NOpn_Pgm'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['CurrentYr_NOpn_Pgm'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr1_NOpn_Pgm'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr2_NOpn_Pgm'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr3_NOpn_Pgm'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr4_NOpn_Pgm'] !!}</td>

            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
                <td style="border: 1px solid #d0d0d0;"></td>
            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px;background-color:#f4f4f4;color:#357EC7; font-weight:500;">Total Free Events Attended</td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Life2date_NFreeEvt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_5Yrs_NFreeEvt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Last_6Mth_NFreeEvt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['CurrentYr_NFreeEvt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr1_NFreeEvt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr2_NFreeEvt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr3_NFreeEvt'] !!}
                </td>
                <td style="border: 1px solid #d0d0d0;background-color:#f4f4f4;text-indent: 1px; direction: rtl;">
                    {!! $record['Prior_Yr4_NFreeEvt'] !!}
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 2px;color:#357EC7;">Triple Sangha</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Life2date_NZM3Fold'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_5Yrs_NZM3Fold'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_6Mth_NZM3Fold'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['CurrentYr_NZM3Fold'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr1_NZM3Fold'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr2_NZM3Fold'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr3_NZM3Fold'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr4_NZM3Fold'] !!}</td>
            </tr>

            <tr>
                <td style="border: 1px solid #d0d0d0;text-indent: 2px;color:#357EC7;">Other Zoom Meetings</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Life2date_NZoom_Ot'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_5Yrs_NZoom_Ot'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Last_6Mth_NZoom_Ot'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['CurrentYr_NZoom_Ot'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr1_NZoom_Ot'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr2_NZoom_Ot'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr3_NZoom_Ot'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-indent: 1px; direction: rtl;">{!! $record['Prior_Yr4_NZoom_Ot'] !!}</td>

            </tr>

        </tbody>
    </table>


@elseif($section == 'detail')
    <table>
        <tbody>
        <tr>
            <th width="14" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Date</th>
            <th width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: center;text-indent: 7px;">Amount</th>
            <th width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Activity Cat 1</th>
            <th width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Activity Cat 2</th>
            <th width="35" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Activity</th>
            <th width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Class</th>
            <th width="35" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Client Message</th>
        </tr>
        @foreach($records as $record)
            <tr>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['Date'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: right;text-indent: 8px;">{!! $record['Amount'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['Activitycat1'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['Activitycat2'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['Activity'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['Class'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['ClientMessage'] !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@elseif($section == 'touch')
    <table>
        <tbody>
        <tr>
            <th width="14" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">DFL Name</th>
            <th width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: center;text-indent: 7px;">Campaign</th>
            <th width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Status</th>
            <th width="25" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Channel</th>
            <th width="15" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Date</th>
            <th width="35" style="border: 1px solid #d0d0d0;background-color:#e1eeff;font-weight: 500;text-align: left;text-indent: 1px;">Comment</th>
        </tr>
        @foreach($records as $record)
            <tr>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['dflname'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: right;text-indent: 8px;">{!! $record['TouchCampaign'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['TouchStatus'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['TouchChannel'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['TouchDate'] !!}</td>
                <td style="border: 1px solid #d0d0d0;text-align: left;text-indent: 1px;">{!! $record['TouchNotes'] !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
