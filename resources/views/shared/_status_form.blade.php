<form action="{{ route('statuses.store') }}" method="POST">
    @include('shared._errors')
    {{ csrf_field() }}
    <label for="content"></label>
    <textarea class="form-control" rows="3" placeholder="Talk about what’s new…"
              name="content" id="content">{{ old('content') }}</textarea>
    <div class="text-end">
        <button type="submit" class="btn btn-primary mt-3">Published</button>
    </div>
</form>
