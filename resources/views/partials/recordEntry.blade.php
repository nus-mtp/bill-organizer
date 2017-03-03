<tr>
    <td>{{ $record->issue_date }}</td>
    <td>{{ $record->period }}</td>
    @if($type === 'billing organization')
        <td>{{ $record->due_date }}</td>
    @endif
    <td>${{ $record->amount }}</td>
    <td class='right aligned'>
        <div class="ui small basic icon buttons">

            <a href="{{ route('show_record_file', $record) }}" class="ui icon button">
                <i class="blue file icon"></i>
            </a>

            <a href="{{ route('download_record_file', $record) }}" class="ui icon download-record button">
                <i class="purple download icon"></i>
            </a>

            <a href="{{ route('delete_record_file', $record) }}" class="ui icon delete-record button">

                <form method="POST" action="{{ route('show_record_file', $record) }}"
                      style="display: none;" id="delete-record">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                </form>

                <i class="red remove icon"></i>
            </a>

        </div>
    </td>
</tr>