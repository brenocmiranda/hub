<table>
    <thead bgcolor="#f3f3f3">
    <tr>
        @if( array_search('created_at', $items) !== false)
            <th align="center">Data</th>
        @endif
        @if( array_search('name', $items) !== false)
            <th align="center">Nome</th>
        @endif
        @if( array_search('slug', $items) !== false)
            <th align="center">Slug</th>
        @endif
        @if( array_search('typeInt', $items) !== false)
            <th align="center">Tipo</th>
        @endif
        @if( array_search('url', $items) !== false)
            <th align="center">URL</th>
        @endif
        @if( array_search('encoded', $items) !== false)
            <th align="center">Enconded</th>
        @endif
        @if( array_search('user', $items) !== false)
            <th align="center">User</th>
        @endif
        @if( array_search('password', $items) !== false)
            <th align="center">Password</th>
        @endif
        @if( array_search('token', $items) !== false)
            <th align="center">Token</th>
        @endif
        @if( array_search('header', $items) !== false)
            <th align="center">Header</th>
        @endif
        @if( array_search('company', $items) !== false)
            <th align="center">Empresa</th>
        @endif
        @if( array_search('active', $items) !== false)
            <th align="center">Status</th>
        @endif
    </tr>
    </thead>
    <tbody>
        @foreach($integrations as $integration)
            <tr>
                @if( array_search('created_at', $items) !== false)
                    <td align="center">{{ $integration->created_at->format("d/m/Y H:i:s") }}</td>
                @endif
                @if( array_search('name', $items) !== false)
                    <td align="center">{{ $integration->name }}</td>
                @endif
                @if( array_search('slug', $items) !== false)
                    <td align="center">{{ $integration->slug }}</td>
                @endif
                @if( array_search('typeInt', $items) !== false)
                    <td align="center">{{ $integration->type }}</td>
                @endif
                @if( array_search('url', $items) !== false)
                    <td align="center">{{ $integration->url }}</td>
                @endif
                @if( array_search('encoded', $items) !== false)
                    <td align="center">{{ $integration->encoded }}</td>
                @endif
                @if( array_search('user', $items) !== false)
                    <td align="center">{{ $integration->user }}</td>
                @endif
                @if( array_search('password', $items) !== false)
                    <td align="center">{{ $integration->password }}</td>
                @endif
                @if( array_search('token', $items) !== false)
                    <td align="center">{{ $integration->token }}</td>
                @endif
                @if( array_search('header', $items) !== false)
                    <td align="center">{{ $integration->header }}</td>
                @endif
                @if( array_search('company', $items) !== false)
                    <td align="center">{{ $integration->RelationCompanies->name }}</td>
                @endif
                @if( array_search('active', $items) !== false)
                    <td align="center">{{ $integration->active ? 'Ativo' : 'Desativado' }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>