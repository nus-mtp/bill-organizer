<a class="green card" href="{{ route('show_record_issuer', $record_issuer) }}">
    <div class="content" style="text-align:center;">
        <p>{{ $record_issuer->name }}</p>
    </div>
    <div class="extra content">
        <form id="deleteBillorgForm" method="POST" action="{{ url('/dashboard/record_issuers/' . $record_issuer->id) }}" style="display: inline;">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button type="submit" class="mini inverted red compact ui icon right floated js-btn-del-billorg button">
                <i class="trash icon"></i>
            </button>
        </form>
    </div>
</a>
