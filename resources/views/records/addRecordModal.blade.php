<div class="ui small add-record modal">
    <i class="close icon"></i>
    <div class="header">Add new record</div>

    <!-- modal content -->
    <div class="content">
        <div class="ui fluid input">
            <form method="POST" action="{{ route('records', $record_issuer) }}"
                  class="ui form" enctype="multipart/form-data" id="add-record">
                <!-- TODO: customize form based on type -->
                <!-- TODO: research on semantic UI calendar -->
                {{ csrf_field() }}
                <div class="field">
                    <label for="record">Upload the record:</label>
                    <input type="file" name="record" id="record">
                </div>

                <div class="field">
                    <label for="issue_date">Issue date:</label>
                    <input type="date" name="issue_date" id="issue_date" placeholder="Issue date">
                </div>

                <div class="field">
                    <label for="period">Record period:</label>
                    <input type="month" name="period" id="period" placeholder="Record period">
                </div>

                @if($type === 'billing organization')
                    <div class="field">
                        <label for="due_date">Due date:</label>
                        <input type="date" name="due_date" id="due_date" placeholder="Due date">
                    </div>
                @endif

                <div class="field">
                    <!-- TODO: customize based on type -->
                    <label for="amount">{{ $amount_field_name }}:</label>
                    <input type="number" name="amount" id="amount" placeholder="{{ $amount_field_name }}">
                </div>

            </form>
        </div><!-- end ui fluid input -->
    </div><!-- end modal content -->

    <div class="actions">
        <div class="ui button approve green" data-value="yes">Add</div>
        <div class="ui button black cancel" data-value="no">Cancel</div>
    </div>

</div><!-- modal end -->