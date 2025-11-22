<x-guest-layout>
    <div class="text-center p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Account Pending Approval</h1>
        <div class="text-gray-600 mb-6">
            <p>Thank you for registering as a Curator.</p>
            <p>Your account is currently under review by the Administrator.</p>
            <p>Please check back later.</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900">
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
