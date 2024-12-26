<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Articles') }}
        </h2>
        {{-- @can('create articles') --}}
        <a href="{{route('article.create')}}" class="bg-slate-700 text-sm rounded-md px-3 py-3 text-white">Create</a>
        {{-- @endcan --}}
    </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-message></x-message>
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="border-b">
                        <th class="px-6 py-5 text-left">#</th>
                        <th class="px-6 py-5 text-left">Title</th>
                        <th class="px-6 py-5 text-left">Image</th>
                        <th class="px-6 py-5 text-left">Author</th>
                        <th class="px-6 py-5 text-left">Created</th>
                        <th class="px-6 py-5 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @if($article->isNotEmpty())
                    @foreach($article as $list)
                    <tr class="border-b">
                        <td class="px-6 py-5 text-left">{{$list->id}}</td>
                        <td class="px-6 py-5 text-left">{{$list->title}}</td>
                        <td>
                            <img src="{{ asset('uploads/students/'.$list->profile_image) }}" width="70px" height="70px" alt="Image">
                        </td>
                        <td class="px-6 py-5 text-left">{{$list->author}}</td>
                        <td class="px-6 py-5 text-left">{{\Carbon\Carbon::parse($list->created_at)->format('d M, Y')}}</td>
                        <td class="px-6 py-5 text-center">
                            {{-- @can('edit articles') --}}
                            <a href="{{route('article.edit',$list->id)}}" class="bg-slate-700 text-sm rounded-md px-3 py-3 text-white">Edit</a>
                            {{-- @endcan --}}
                            {{-- @can('delete articles') --}}
                            {{-- <a href="javascript:void(0)" onclick="deleteArticle({{$list->id}})" class="bg-red-500 text-sm rounded-md px-3 py-3 text-white">Delete</a> --}}
                            {{-- <a href="javascript:void(0)" onclick="deleteArticles()" class="bg-red-500 text-sm rounded-md px-3 py-3 text-white">Delete</a> --}}
                            <a href="javascript:void(0)"
       onclick="deleteArticle({{ $list->id }})"
       class="bg-red-500 text-sm rounded-md px-3 py-3 text-white">
        Delete
    </a>
                            {{-- @endcan --}}
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
            <div class="my-3">
            {{$article->links()}}
        </div>
        </div>
    </div>
    {{-- <x-slot name="script">
        <script type="text/javascript">

            function deleteArticle(id){

                if(confirm('Are you want to delete')){
                    $.ajax({
                        url:'{{route("article.destroy")}}',
                        type:'delete',
                        data:{id:id},
                        dataType:'json',
                        headers:{
                            'x-csrf-token':'{{csrf_token()}}'
                        },
                        success: function(response){
                            window.location.href ='{{route("article.index")}}'
                        }
                    });
                }

            }
        </script>
    </x-slot> --}}
    <x-slot name="script">
        <script type="text/javascript">
            function deleteArticle(id) {
                if (confirm('Are you sure you want to delete this article?')) {
                    $.ajax({
                        url: '{{ route("article.destroy") }}',
                        type: 'DELETE',
                        data: { id: id },
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.success) {
                                alert(response.message);
                                window.location.reload();
                            } else {
                                alert('Failed to delete the article. Please try again.');
                            }
                        },
                        error: function (xhr) {
                            alert('An error occurred. Please try again.');
                        }
                    });
                }
            }
        </script>
    </x-slot>
</x-app-layout>
