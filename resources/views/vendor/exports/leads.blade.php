<table>
    <thead bgcolor="#f3f3f3">
    <tr>
        @if( array_search('created_at', $items) !== false)
            <th align="center">Data</th>
        @endif
        @if( array_search('name', $items) !== false)
            <th align="center">Nome</th>
        @endif
        @if( array_search('email', $items) !== false)
            <th align="center">Email</th>
        @endif
        @if( array_search('phone', $items) !== false)
            <th align="center">Telefone</th>
        @endif
        @if( array_search('origin', $items) !== false)
            <th align="center">Origem</th>
        @endif
        @if( array_search('building_id', $items) !== false)
            <th align="center">Empreendimento</th>
        @endif
        @if( array_search('company', $items) !== false)
            <th align="center">Empresa</th>
        @endif
        @if( array_search('utm_source', $items) !== false)
            <th align="center">Utm_source</th>
        @endif
        @if( array_search('utm_medium', $items) !== false)
            <th align="center">Utm_medium</th>
        @endif
        @if( array_search('utm_campaign', $items) !== false)
            <th align="center">Utm_campaign</th>
        @endif
        @if( array_search('utm_content', $items) !== false)
            <th align="center">Utm_content</th>
        @endif
        @if( array_search('utm_term', $items) !== false)
            <th align="center">Utm_term</th>
        @endif
        @if( array_search('url', $items) !== false)
            <th align="center">URL</th>
        @endif
        @if( array_search('message', $items) !== false)
            <th align="center">Mensagem</th>
        @endif
        @if( array_search('partyNumber', $items) !== false)
            <th align="center">PartyNumber</th>
        @endif
        @if( array_search('srNumber', $items) !== false)
            <th align="center">SrNumber</th>
        @endif
        @if( array_search('idCaso', $items) !== false)
            <th align="center">idCaso</th>
        @endif
    </tr>
    </thead>
    <tbody>
        @foreach($leads as $lead)
            <tr>
                @if( array_search('created_at', $items) !== false)
                    <td align="center">{{ $lead->created_at->format("d/m/Y H:i:s") }}</td>
                @endif
                @if( array_search('name', $items) !== false)
                    <td align="center">{{ $lead->name }}</td>
                @endif
                @if( array_search('email', $items) !== false)
                    <td align="center">{{ $lead->email }}</td>
                @endif
                @if( array_search('phone', $items) !== false)
                    <td align="center">{{ $lead->phone }}</td>
                @endif
                @if( array_search('origin', $items) !== false)
                    <td align="center">{{ $lead->RelationOrigins->name }}</td>
                @endif
                @if( array_search('building_id', $items) !== false)
                    <td align="center">{{ $lead->RelationBuildings->name }}</td>
                @endif
                @if( array_search('company', $items) !== false)
                    <td align="center">{{ $lead->RelationCompanies->name }}</td>
                @endif
                @if( array_search('utm_source', $items) !== false )
                    <td align="center">{{ $lead->RelationFields->where('name', 'utm_source')->last() ? $lead->RelationFields->where('name', 'utm_source')->last()->value : "" }}</td>
                @endif
                @if( array_search('utm_medium', $items) !== false)
                    <td align="center">{{ $lead->RelationFields->where('name', 'utm_medium')->last() ? $lead->RelationFields->where('name', 'utm_medium')->last()->value : "" }}</td>
                @endif
                @if( array_search('utm_campaign', $items) !== false)
                    <td align="center">{{ $lead->RelationFields->where('name', 'utm_campaign')->last() ? $lead->RelationFields->where('name', 'utm_campaign')->last()->value : "" }}</td>
                @endif
                @if( array_search('utm_content', $items) !== false)
                    <td align="center">{{ $lead->RelationFields->where('name', 'utm_content')->last() ? $lead->RelationFields->where('name', 'utm_content')->last()->value : "" }}</td>
                @endif
                @if( array_search('utm_term', $items) !== false)
                    <td align="center">{{ $lead->RelationFields->where('name', 'utm_term')->last() ? $lead->RelationFields->where('name', 'utm_term')->last()->value : "" }}</td>
                @endif
                @if( array_search('url', $items) !== false)
                    <td align="center">{{ $lead->RelationFields->where('name', 'url')->last() ? $lead->RelationFields->where('name', 'url')->last()->value : "" }}</td>
                @endif
                @if( array_search('message', $items) !== false)
                    <td align="center">{{ $lead->RelationFields->where('name', 'message')->last() ? $lead->RelationFields->where('name', 'message')->last()->value : "" }}</td>
                @endif
                @if( array_search('partyNumber', $items) !== false)
                    <td align="center">{{ $lead->RelationFields->where('name', 'PartyNumber')->last() ? $lead->RelationFields->where('name', 'PartyNumber')->last()->value : "" }}</td>
                @endif
                @if( array_search('srNumber', $items) !== false)
                    <td align="center">{{ $lead->RelationFields->where('name', 'SrNumber')->last() ? $lead->RelationFields->where('name', 'SrNumber')->last()->value : "" }}</td>
                @endif
                @if( array_search('idCaso', $items) !== false)
                    <td align="center">{{ $lead->RelationFields->where('name', 'idCaso')->last() ? $lead->RelationFields->where('name', 'idCaso')->last()->value : "" }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>