<div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 h-full flex flex-col">
    <h2 class="text-base font-semibold mb-3 text-clipzo-dark shrink-0">Akun Admin</h2>
    
    <div class="flex-1 overflow-y-auto min-h-0 pr-2">
        <ul class="text-xs space-y-3 text-clipzo-dark">
            @foreach ($users as $user)
                <div class="flex justify-between items-center">
                    <div class="flex justify-start space-x-2 items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                        </svg>
                        <div class="flex flex-col space-y-1">
                            <span class="font-medium text-sm">{{ $user->username }}</span>
                            <span class="font-regular text-gray-600">{{ ucfirst($user['branch_location']) }}</span> 
                        </div>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded bg-green-100 text-green-700 shrink-0">Beroperasi</span>
                </div>
            @endforeach
        </ul>
    </div>
</div>