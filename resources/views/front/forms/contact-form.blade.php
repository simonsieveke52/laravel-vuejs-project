<form action="{{ route('contact') }}" method="POST">
    @csrf
    <div class="px-3 messages">
        {{ session('status') }}
    </div>
    {{-- @include('layouts.errors-and-messages') --}}
    <div class="modal-body row">
        <div class="col-12 pb-2 relative">
            <label class="col-12 px-0" for="modal--contact_name">Name</label>
            <input class="col-12" type="text" name="contact_name" id="modal--contact_name" class="modal--contact-us__input--name">                
            <div class="col-12 py-1 px-0 text-red font-weight-bold">
                {{ $errors->first('contact_name') }}
            </div>
        </div>
        <div class="col-sm-6 pb-4 relative">
            <label class="col-12 px-0" for="modal--contact_email">Email</label>
            <input class="col-12" type="email" name="contact_email" id="modal--contact_email" class="modal--contact-us__input--email">
            <div class="col-12 py-1 px-0 text-red font-weight-bold text-red font-weight-bold">
                {{ $errors->first('contact_email') }}
            </div>
        </div>
        <div class="col-sm-6 pb-4 relative">
            <label class="col-12 px-0" for="modal--contact_phone">Phone Number</label>
            <input class="col-12" type="phone" name="contact_phone" id="modal--contact_phone" class="modal--contact-us__input--phone">
            <div class="col-12 py-1 px-0 text-red font-weight-bold">
                {{ $errors->first('contact_phone') }}
            </div>
        </div>
        <div class="col-12 pb-4 relative">
            <label class="col-12 px-0" for="modal--contact_message">Message</label>
            <textarea class="col-12" name="contact_message" id="modal--contact_message" class="modal--contact-us__input--message"></textarea>
            <div class="col-12 py-1 px-0 text-red font-weight-bold">
                {{ $errors->first('contact_message') }}
            </div>
        </div>
        <div class="col-12 mb-3">
            <button type="submit" class="modal--contact-us__button link btn btn-highlight text-white px-3 py-2">Submit</button>
        </div>
    </div>
</form>