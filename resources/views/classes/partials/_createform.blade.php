@php
    use App\Helpers\CommonHelper;
@endphp

<form method="POST" id="classForm" action="{{ route('classes.store') }}">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        @csrf
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>Class No</label>
                <input type="text" name="class_no"
                    class="form-control @error('class_no') border border-danger @enderror" id="class_no" required
                    value="{{old('class_no')}}" />
                @error('class_no')
                    <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>Class Name </label>
                <input type="text" name="class_name"
                    class="form-control @error('class_name') border border-danger @enderror" id="class_name" required
                    value="{{old('class_name')}}" />
                @error('class_name')
                    <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>Fee Amount </label>
                <input type="text" name="fee_amount"
                    class="form-control @error('fee_amount') border border-danger @enderror" id="class_name" required
                    value="{{old('fee_amount')}}" />
                @error('fee_amount')
                    <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                <button type="submit" id="saveClassBtn" class="btn btn-sm btn-success">Submit</button>
            </div>
        </div>
    </div>
</form>

<div id="modalbodytag">

</div>


<!-- @section('script')
<script>
    // Only attach event if the button exists
    createModal(
        'createClassModal', // Modal ID
        'Add New Class',    // Modal Title
        '{{ route('classes.createForm') }}'
    );

    // Once the modal is shown, handle form submission inside it
    $(document).on('click', '#saveClassBtn', function (e) {
        e.preventDefault();
        handleFormSubmission(
            'createClassModal',  // Modal ID
            '#classForm',        // Form ID in the modal
            '#class_id'          // Dropdown ID to update with new data
        );
    });
</script>
@endsection -->