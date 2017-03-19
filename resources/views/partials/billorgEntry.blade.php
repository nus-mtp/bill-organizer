<a class="green card" href="{{ route('show_record_issuer', $record_issuer) }}">
    <div class="content" style="text-align:center;">
        <p>{{ $record_issuer->name }}</p>
    </div>
    <div class="extra content">
        <form method="POST" action="{{ url('/dashboard/record_issuers/' . $record_issuer->id) }}" style="display: inline;">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button type="submit" class="mini inverted red compact ui icon right floated button">
                <i class="trash icon"></i>
            </button>
        </form>
        <!--// Dummy button, can comment this out if not implemented
        <button type="submit" class="mini compact ui icon right floated button">
            <i class="edit icon"></i>
        </button>-->
    </div>
</a>

<!--
<a class="green card" href="{{ route('show_record_issuer', $record_issuer) }}">
    <div class="content" style="text-align:center;">
        <p>{{ $record_issuer->name }}</p>
    </div>
    <div class="extra content">
            <button class="mini inverted red compact ui icon right floated del-bill-org button">
                <i class="trash icon"></i>
            </button>
    </div>
</a>-->
