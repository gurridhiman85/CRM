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

    <p>Please find attached {{ $recData->clientname }} campaign {{ $recData->listShortName }} shared by {{ $recData->sharedByName }} ({{ $recData->sharedByEmail }})</p>

    <p>Do not reply to this email. Please contact esupport@datasquare.com if you have any questions.</p>


    Thank You,
    <br/>
    {{ $recData->sender }}
    <br/>
    {{ $recData->senderEmail }}
</div>
