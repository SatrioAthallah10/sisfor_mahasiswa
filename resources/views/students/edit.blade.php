@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto border border-outline-variant bg-surface shadow-2xl relative overflow-hidden flex flex-col rounded">
    <div class="h-1 w-full bg-primary"></div>
    
    <div class="px-8 py-6 border-b border-outline-variant flex justify-between items-start bg-surface-bright shrink-0">
        <div>
            <h2 class="font-headline-md text-headline-md text-on-surface">{{ __('Edit Student Record') }}</h2>
            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest mt-2">{{ __('Modify academic and personal details') }}</p>
        </div>
        <a href="{{ route('students.index') }}" class="text-on-surface-variant hover:text-primary transition-colors mt-1 group">
            <span class="material-symbols-outlined group-hover:rotate-90 transition-transform duration-300">close</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="mx-8 mt-8 p-4 bg-error-container text-on-error-container border border-error rounded-sm text-body-md font-body-md">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="mx-8 mt-8 p-4 bg-error-container text-on-error-container border border-error rounded-sm text-body-md font-body-md">
            {{ session('error') }}
        </div>
    @endif

    <div class="px-8 py-8 flex-1">
        <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex flex-col">
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2" for="nim">{{ __('Student ID (NIM)') }}</label>
                    <input class="bg-transparent border-0 border-b border-outline-variant focus:border-on-surface focus:ring-0 px-0 py-2 font-body-lg text-body-lg text-on-surface placeholder:text-on-surface-variant/40 transition-colors" id="nim" name="nim" value="{{ old('nim', $student->nim) }}" placeholder="e.g. 06.2024.1.07770" type="text" required/>
                </div>
                <div class="flex flex-col">
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2" for="name">{{ __('Full Legal Name') }}</label>
                    <input class="bg-transparent border-0 border-b border-outline-variant focus:border-on-surface focus:ring-0 px-0 py-2 font-body-lg text-body-lg text-on-surface placeholder:text-on-surface-variant/40 transition-colors" id="name" name="name" value="{{ old('name', $student->name) }}" placeholder="Enter full name" type="text" required/>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex flex-col relative">
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2" for="prodi">{{ __('Study Program (Prodi)') }}</label>
                    <select class="appearance-none bg-transparent bg-none border-0 border-b border-outline-variant focus:border-on-surface focus:ring-0 px-0 py-2 font-body-lg text-body-lg text-on-surface transition-colors cursor-pointer pr-8" id="prodi" name="prodi" required>
                        <option disabled value="">{{ __('Select a program') }}</option>
                        <option value="Teknik Informatika" {{ old('prodi', $student->prodi) == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                        <option value="Sistem Informasi" {{ old('prodi', $student->prodi) == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                        <option value="Desain Komunikasi Visual" {{ old('prodi', $student->prodi) == 'Desain Komunikasi Visual' ? 'selected' : '' }}>Desain Komunikasi Visual</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-0 bottom-2 text-on-surface-variant pointer-events-none">arrow_drop_down</span>
                </div>
                <div class="flex flex-col">
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2" for="gpa">{{ __('Current GPA (IPK)') }}</label>
                    <input class="bg-transparent border-0 border-b border-outline-variant focus:border-on-surface focus:ring-0 px-0 py-2 font-body-lg text-body-lg text-on-surface placeholder:text-on-surface-variant/40 transition-colors font-variant-numeric: tabular-nums;" id="gpa" max="4.00" min="0.00" name="gpa" value="{{ old('gpa', $student->gpa) }}" placeholder="e.g. 3.85" step="0.01" type="number" required/>
                </div>
            </div>

            <div class="w-full relative py-4">
                <div class="absolute inset-0 flex items-center justify-center flex-col gap-[2px]">
                    <div class="w-full h-[1px] bg-outline-variant"></div>
                    <div class="w-full h-[3px] bg-outline-variant"></div>
                </div>
            </div>

            @if ($student->photo_path)
                <div class="flex items-center gap-4 bg-surface-container-low p-4 rounded border border-outline-variant">
                    <img src="{{ $student->photo_url }}" class="w-16 h-16 object-cover rounded-full border border-outline" alt="Current photo"/>
                    <div>
                        <p class="font-label-sm text-label-sm uppercase tracking-wider text-primary font-bold">{{ __('Current Profile Photo') }}</p>
                        <p class="font-body-md text-sm text-on-surface-variant mt-1">{{ __('Uploading a new photo below will replace this existing file.') }}</p>
                    </div>
                </div>
            @endif

            <div class="flex flex-col">
                <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-4">{{ __('Replace Profile Photo (Optional)') }}</label>
                <div class="border border-dashed border-outline-variant bg-surface-container-lowest hover:bg-surface-container-low transition-colors duration-300 p-8 flex flex-col items-center justify-center text-center cursor-pointer group relative" id="upload-area">
                    <input type="file" name="photo" id="photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*"/>
                    <div id="upload-content">
                        <div class="w-12 h-12 rounded-full bg-surface-variant flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <span class="material-symbols-outlined text-on-surface-variant">cloud_upload</span>
                        </div>
                        <p class="font-body-md text-body-md text-on-surface mb-1">{{ __('Click or drag student portrait here') }}</p>
                        <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">{{ __('to select new image file') }}</p>
                        <p class="font-label-sm text-label-sm text-on-surface-variant mt-4 opacity-70">{{ __('Max size 2MB. Accepted formats: JPG, PNG.') }}</p>
                    </div>
                    <div id="upload-preview" class="hidden flex flex-col items-center justify-center text-center">
                        <img id="preview-image" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg mb-4 border border-outline mx-auto"/>
                        <p class="font-body-md text-body-md text-primary font-semibold mb-2">{{ __('Preview:') }}</p>
                        <p id="file-name" class="font-body-sm text-body-sm text-on-surface-variant"></p>
                        <p class="font-label-sm text-label-sm text-on-surface-variant mt-2 opacity-70">{{ __('Click to change file') }}</p>
                    </div>
                    <div id="upload-loading" class="hidden flex flex-col items-center gap-3">
                        <div class="w-10 h-10 border-4 border-outline-variant border-t-primary rounded-full animate-spin"></div>
                        <p class="font-body-md text-body-md text-on-surface">{{ __('Uploading...') }}</p>
                    </div>
                </div>
                <div id="upload-error" class="hidden mt-4 p-3 bg-error-container text-on-error-container border border-error rounded-sm text-body-sm font-body-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">error</span>
                    <span id="upload-error-text"></span>
                </div>
            </div>

            <div class="pt-6 border-t border-outline-variant bg-surface-bright flex justify-end items-center gap-4 shrink-0">
                <a href="{{ route('students.index') }}" class="px-6 py-2 border border-on-surface text-on-surface font-label-lg text-label-lg uppercase tracking-wider hover:bg-surface-variant transition-colors duration-300">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="px-8 py-2 bg-on-surface text-surface border border-on-surface font-label-lg text-label-lg uppercase tracking-wider hover:bg-primary hover:border-primary hover:text-on-primary transition-all duration-300 shadow-[0_4px_14px_0_rgba(37,36,34,0.1)] hover:shadow-[0_6px_20px_rgba(167,52,0,0.2)]">
                    {{ __('Save Changes') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const photoInput = document.getElementById('photo');
    const uploadArea = document.getElementById('upload-area');
    const uploadContent = document.getElementById('upload-content');
    const uploadPreview = document.getElementById('upload-preview');
    const uploadLoading = document.getElementById('upload-loading');
    const previewImage = document.getElementById('preview-image');
    const fileName = document.getElementById('file-name');
    const uploadError = document.getElementById('upload-error');
    const uploadErrorText = document.getElementById('upload-error-text');

    // Handle file selection
    photoInput.addEventListener('change', handleFileSelect);

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('border-primary', 'bg-primary-container');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('border-primary', 'bg-primary-container');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('border-primary', 'bg-primary-container');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            photoInput.files = files;
            handleFileSelect();
        }
    });

    function handleFileSelect() {
        const file = photoInput.files[0];
        if (!file) return;

        // Validate file type
        if (!file.type.startsWith('image/')) {
            showUploadError('{{ __("Please select an image file") }}');
            photoInput.value = '';
            return;
        }

        // Validate file size (2MB max)
        if (file.size > 2048 * 1024) {
            showUploadError('{{ __("File size must not exceed 2MB") }}');
            photoInput.value = '';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.src = e.target.result;
            fileName.textContent = file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)';
            uploadContent.classList.add('hidden');
            uploadPreview.classList.remove('hidden');
            uploadError.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    function showUploadError(message) {
        uploadErrorText.textContent = message;
        uploadError.classList.remove('hidden');
        uploadLoading.classList.add('hidden');
        uploadPreview.classList.add('hidden');
        uploadContent.classList.remove('hidden');
    }

    // Handle form submission
    const form = document.querySelector('form');
    form.addEventListener('submit', function() {
        if (photoInput.files.length > 0) {
            uploadError.classList.add('hidden');
            uploadContent.classList.add('hidden');
            uploadPreview.classList.add('hidden');
            uploadLoading.classList.remove('hidden');
        }
    });
</script>
@endsection
