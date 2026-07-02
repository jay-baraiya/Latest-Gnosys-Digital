<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : (isset($ticket) ? 'Edit' : 'Create') }}">

        @php
        $url = isset($ticket) ? route('admin.tickets.update', encrypt($ticket->id)) : route('admin.tickets.store');
        @endphp

        <div class="card border-0">
            <div class="card-body pb-0 pt-0 px-2">
                <ul class="nav nav-tabs nav-bordered nav-bordered-primary">
                    <li class="nav-item me-3">
                        <a href="{{ $url }}?tab=ticket-form" data-tab="ticket-form"
                            class="nav-link p-2 {{ $tab == 'ticket-form' ? 'active' : '' }}">
                            <i class="ti ti-ticket  me-2"></i>Ticket
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a href="{{ $url }}?tab=task-form" data-tab="task-form"
                            class="nav-link p-2 {{ $tab == 'task-form' ? 'active' : '' }}">
                            <i class="ti ti-list me-2"></i>Tasks
                        </a>
                    </li>
                    @if (!empty($ticket->id))
                        <li class="nav-item me-3">
                            <a href="{{ $url }}?tab=chats-form" data-tab="chats-form"
                                class="nav-link p-2 {{ $tab == 'chats-form' ? 'active' : '' }}">
                                <i class="ti ti-messages me-2"></i>Chats
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="row tabHide" id="ticket-form" style="display: {{ $tab != 'ticket-form' ? 'none' : '' }};">
            @include('admin.ticket.parts.ticket')
        </div>

        <div class="row tabHide" id="task-form" style="display: {{ $tab != 'task-form' ? 'none' : '' }};">
            @include('admin.ticket.parts.task')
        </div>

        @if (!empty($ticket->id))
            <div class="row tabHide" id="chats-form" style="display: {{ $tab != 'chats-form' ? 'none' : '' }};">
                @include('admin.ticket.parts.chat')
            </div>
        @endif

    </x-form-wrapper>

    @push('scripts')
    <script>
        Dropzone.autoDiscover = false;
        $(document).ready(function () {

            $(document).on('click', '.nav-link', function (e) {
                e.preventDefault();

                var tab = $(this).data('tab');

                var url = new URL(window.location.href);
                url.searchParams.set('tab', tab);

                window.history.replaceState({}, '', url);


                $('.nav-link').removeClass('active');
                $(this).addClass('active');

                $('.tabHide').hide();
                $(`#${tab}`).show();
            });

        });
    </script>

    @include('admin.ticket.ticket-script')

    @include('admin.ticket.task-script')
    @endpush

</x-master-layout>
