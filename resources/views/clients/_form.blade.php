<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name *</label>
        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $client->first_name ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
        @error('first_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name *</label>
        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $client->last_name ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
        @error('last_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth *</label>
        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', isset($client) ? $client->date_of_birth->format('Y-m-d') : '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
        @error('date_of_birth') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="gender" class="block text-sm font-medium text-gray-700">Gender *</label>
        <select name="gender" id="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            <option value="">Select...</option>
            <option value="male" {{ old('gender', $client->gender ?? '') === 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ old('gender', $client->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
            <option value="other" {{ old('gender', $client->gender ?? '') === 'other' ? 'selected' : '' }}>Other</option>
        </select>
        @error('gender') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700">Phone *</label>
        <input type="text" name="phone" id="phone" value="{{ old('phone', $client->phone ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $client->email ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
        <textarea name="address" id="address" rows="2"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('address', $client->address ?? '') }}</textarea>
        @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="medical_notes" class="block text-sm font-medium text-gray-700">Medical Notes (Allergies, Conditions)</label>
        <textarea name="medical_notes" id="medical_notes" rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('medical_notes', $client->medical_notes ?? '') }}</textarea>
        @error('medical_notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>
