@extends('layout.mainlayout_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-md-3 border-end vh-100">
            <h5 class="mt-2">{{ $company->name }} Mail</h5>
            <ul class="nav flex-column">
                @foreach($folders as $f)
                    <li class="nav-item">
                        <a class="nav-link {{ isset($folder) && $folder == $f->name ? 'active' : '' }}"
                           href="{{ route('company.mail.folder',['company'=>current_company_id(),'folder'=>$f->name]) }}">
                           {{ $f->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <hr>
            <button class="btn btn-primary w-100" data-bs-toggle="collapse" data-bs-target="#composeForm">
                Compose
            </button>
            <div class="collapse mt-2" id="composeForm">
                <form action="{{ route('company.mail.send',['company'=>current_company_id()]) }}" method="POST">
                    @csrf
                    <input type="email" name="to" placeholder="To" class="form-control mb-2" required>
                    <input type="text" name="subject" placeholder="Subject" class="form-control mb-2" required>
                    <textarea name="body" rows="5" placeholder="Message" class="form-control mb-2" required></textarea>
                    <button class="btn btn-success w-100">Send</button>
                </form>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="col-md-9 vh-100 overflow-auto">
            @if(isset($messages))
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $message)
                        <tr>
                            <td>{{ $message->getFrom()[0]->mail }}</td>
                            <td>
                                <a href="{{ route('company.mail.message',['company'=>current_company_id(),'id'=>$message->getUid()]) }}">
                                    {{ $message->getSubject() }}
                                </a>
                            </td>
                            <td>{{ $message->getDate() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @elseif(isset($message))
                <h5>From: {{ $message->getFrom()[0]->mail }}</h5>
                <h6>Subject: {{ $message->getSubject() }}</h6>
                <p>Date: {{ $message->getDate() }}</p>
                <hr>
                <div>{!! $message->getHTMLBody() ?? $message->getTextBody() !!}</div>
            @else
                <p class="text-muted mt-3">Select a folder to view messages.</p>
            @endif
        </div>
    </div>
</div>
@endsection
