<!-- Modal cancel_friendship -->
    <div class="modal fade m-t-180" id="cancel-modal{{ $user->reference }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <!--Content-->
            <div class="modal-content">
                <!--Header-->
                <div class="modal-header bg-brand text-right">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                </div>
                <!--Body-->
                <div class="modal-body bg-brand-lite c-dark dis-flex">
                    <p class="text-responsive w-700 m-0">Are you sure you want to cancel this friendship?</p>
                </div>
                <!--Footer-->
                <div class="modal-footer bg-brand-lite justify-content-center">
                    <a class="btn btn-md btn-outline-brand cancel-friendship" href="{{ route('unfriend', $user->reference) }}" data-reference="{{ $user->reference }}" >Yes</a>
                    <button type="button" class="btn btn-md btn-outline-brand" data-dismiss="modal">No</button>
                </div>
            </div>
            <!--/.Content-->
        </div>
    </div>
<!-- Modal -->
<!-- Modal cancel_request -->
    <div class="modal fade m-t-180" id="cancel-request-modal{{ $user->reference }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <!--Content-->
            <div class="modal-content">
                <!--Header-->
                <div class="modal-header bg-brand text-right">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                </div>
                <!--Body-->
                <div class="modal-body bg-brand-lite c-dark dis-flex">
                    <p class="text-responsive w-700 m-0">Are you sure you want to cancel this friend request?</p>
                </div>
                <!--Footer-->
                <div class="modal-footer bg-brand-lite justify-content-center">
                    <a class="btn btn-md btn-outline-brand cancel-friend-request" href="{{ route('cancel_friend_request', $user->reference) }}" data-reference="{{ $user->reference }}">Yes</a>
                    <button type="button" class="btn btn-md btn-outline-brand" data-dismiss="modal">No</button>
                </div>
            </div>
            <!--/.Content-->
        </div>
    </div>
<!-- Modal -->