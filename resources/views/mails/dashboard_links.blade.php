<style>
    .email-content{
        width: 100%;
        margin: 0 auto;
        font-family: Open Sans,Arial,sans-serif;
        font-size: 13px;
        font-weight: normal;
        line-height: 1.4em;
        color: #444;
    }
</style>
<div class="email-content">
    Hello {{ $recData->receiver }},

    <p>{{ $recData->limitedtextarea1 }}</p>

    <ul>
        @foreach($recData->links as $link)
            @if(!empty($link['name']))
                <li style="list-style: none;">
                    <p>Name : {{ $link['name'] }}</p>
                    <p>Link : <a href="{{ $link['link'] }}" target="_blank">{{ $link['link'] }}</a></p>
                    <p>Expire on :
                        @if(!empty($link['expire_at']))
                            {{ date("F d, Y h:i:s A",strtotime($link['expire_at'])) }}
                        @endif</p>
                    <hr>
                </li>
            @endif

        @endforeach
    </ul>

    <p>Do not reply to this email. Please contact esupport@datasquare.com if you have any questions.</p>

    Thank You,
    <br/>
    {{ $recData->sender }}
</div>
