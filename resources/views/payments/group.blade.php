@extends("main")

@section("title", "Mnou vytvořené skupiny plateb")

@section("actions")
    @include("payments.includes.actions")
@endsection

@section("content")
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Název skupiny</th>
            <th scope="col">Akce</th>
        </tr>
        </thead>
        <tbody>
    @forelse($data as $group)
        <tr>
            <td>{{$group->name}}</td>
            @if($group->author == $username || $user->hasPermissionTo('payments.group'))
                <td>
                    <a data-bs-toggle="tooltip" data-bs-title="Zobrazit detail skupiny" href="{{url("/payment/group/$group->id")}}" class="text-decoration-none"><i class="ti ti-info-circle"></i></a>
                </td>
            @endif
        </tr>
        @empty
        <tr>
            <td colspan="6">Žádné platby nebyly nalezeny.</td>
        </tr>
    @endforelse
        </tbody>
    </table>

    {{ $data->links() }}


@endsection
