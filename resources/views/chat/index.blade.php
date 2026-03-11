@extends('layout.layout')

@section('main')
<div ng-controller="chatCtrl" ng-init="init();" class="mt-24">
    <div class="d-flex justify-content-between align-items-center mb-16">
        <h5 class="mb-0">Chat System</h5>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-4">
                    <label class="form-label">Users</label>
                    <input
                        type="text"
                        class="form-control mb-12"
                        placeholder="Search user"
                        ng-model="searchUser">

                    <div class="border rounded p-2" style="height: 480px; overflow-y: auto;">
                        <button
                            type="button"
                            class="btn w-100 text-start mb-2"
                            ng-repeat="u in users | filter: {name: searchUser}"
                            ng-class="selectedUser && selectedUser.id === u.id ? 'btn-primary' : 'btn-outline-secondary'"
                            ng-click="selectUser(u)">
                            <div class="fw-semibold">@{{ u.name }}</div>
                            <div class="small opacity-75">@{{ u.email }}</div>
                        </button>
                        <div class="text-secondary text-center py-4" ng-if="!users.length">
                            No users available for chat.
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <label class="form-label">Conversation</label>
                    <div id="chatThreadBox" class="border rounded p-3 bg-neutral-50" style="height: 480px; overflow-y: auto;">
                        <div ng-if="!selectedUser" class="text-secondary text-center py-5">
                            Select a user to start chat.
                        </div>

                        <div ng-if="selectedUser">
                            <div
                                ng-repeat="m in messages track by m.id"
                                class="d-flex mb-2"
                                ng-class="m.is_me ? 'justify-content-end' : 'justify-content-start'">
                                <div
                                    class="p-10 radius-8"
                                    ng-class="m.is_me ? 'bg-primary-600 text-white' : 'bg-white border'">
                                    <div style="white-space: pre-wrap;">@{{ m.message }}</div>
                                    <div class="small mt-1" ng-class="m.is_me ? 'text-white-50' : 'text-secondary'">
                                        @{{ m.created_at }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-secondary text-center py-4" ng-if="!messages.length">
                                No messages yet.
                            </div>
                        </div>
                    </div>

                    <div class="mt-3" ng-if="selectedUser">
                        <div class="input-group">
                            <textarea
                                class="form-control"
                                rows="2"
                                ng-model="draft.message"
                                placeholder="Type your message..."
                                ng-keydown="handleEnter($event)"></textarea>
                            <button class="btn btn-primary" type="button" ng-click="sendMessage()" ng-disabled="sending">
                                @{{ sending ? 'Sending...' : 'Send' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
