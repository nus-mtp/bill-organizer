<div class="four wide column">
    <div class="dotted-container">

        <form method="POST" action="{{ url('/dashboard/record_issuers/' . $record_issuer->id) }}"
              style="display: inline;">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button type="submit" class="circular red ui icon right button">
                <i class="remove icon"></i>
            </button>
        </form>

        <p><a href="{{ route('show_record_issuer', $record_issuer) }}">{{ $record_issuer->name }}</a></p>

    </div>
</div>