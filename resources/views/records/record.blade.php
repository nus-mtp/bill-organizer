<tr>
    <td>{{ $record->issue_date->toFormattedDateString() }}</td>
    <td>{{ $record->period->format('F Y') }}</td>
    @if($type === 'billing organization')
        <td>{{ $record->due_date->toFormattedDateString() }}</td>
    @endif
    <td>${{ $record->amount }}</td>
    <td style="text-align: right; width: 1%">
        <div class="ui small basic icon buttons">
            <a href="{{ route('show_record_file', $record) }}" class="ui button">
                <i class="file icon"></i>
            </a>
            <a href="{{ route('download_record_file', $record) }}" class="ui button">
                <i class="download icon"></i>
            </a>
            <a href="{{ route('delete_record_file', $record) }}" onclick="event.preventDefault();
                                            document.getElementById('delete-record').submit()" class="ui button">
                <form method="POST" action="{{ route('show_record_file', $record) }}"
                      style="display: none;" id="delete-record">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                </form>

                <i class="red trash icon"></i>
            </a>
        </div>
    </td>
</tr>