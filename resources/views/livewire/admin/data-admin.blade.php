<div>

  @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session()->has('successdelete'))
<div class="alert alert-warning alert-dismissible" role="alert">
    {{ session('successdelete') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
    <div class="card">

        <div class="row align-items-center">
            <div class="col-auto me-auto">
                <h5 class="card-header">Data Admin</h5>
            </div>
            <div class="col-10 mb-5 ms-5 col-sm-6 col-md-4 col-lg-4  my-sm-0 me-sm-5">
                
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control" placeholder="Cari nama atau email">
                    </div>
            </div>
        </div>
    
   
        <div class="table-responsive text-nowrap">

            <table class="table table-responsive-sm-text">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($admin as $index => $value)
                        <tr>
    
                            <td class="text-4">{{ $admin->firstItem() + $index }}</td>
                            <td>{{ $value->name }}</td>
                            <td>{{ $value->email }}</td>


                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.dataAdmin.edit',$value->id) }}">
                                            <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                        </a>

                                        <button type="button" 
                                                class="dropdown-item" 
                                                wire:click="deleteAdmin({{ $value->id }})"
                                                wire:confirm="Apakah Anda yakin ingin menghapus data ini?">
                                            <i class="icon-base bx bx-trash me-1"></i> Delete
                                        </button>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data Admin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body ">
         {{ $admin->links() }}
        </div>
    </div>
</div>


