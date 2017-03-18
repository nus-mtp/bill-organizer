<a class="green card" href="{{ route('show_record_issuer', $record_issuer) }}">
    <div class="content" style="text-align:center;">
        <p>{{ $record_issuer->name }}</p>
    </div>
    <div class="extra content">
        <form method="POST" action="{{ url('/dashboard/record_issuers/' . $record_issuer->id) }}"
              style="display: inline;">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button class="mini inverted red compact ui icon right floated del-bill-org button">
                <i class="trash icon"></i>
            </button>
        </form>
    </div>
</a>

<div class="ui small record-issuer-del-cfm modal">
    <i class="close icon"></i>
        <div class="content">
            <form method="POST" action="{{ url('/dashboard/record_issuers/' . $record_issuer->id) }}"
              style="display: inline;">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button type="submit" class="mini inverted red compact ui icon right floated button">
                <i class="trash icon"></i>
            </button>
        </form>
            <div class="actions">
            <div class="ui button approve green" data-value="yes">Add</div>
            <div class="ui button black cancel" data-value="no" onclick="$('form').form('reset'); $('.form .message').html('');">Cancel</div>
        </div>
    </div>
</div>
