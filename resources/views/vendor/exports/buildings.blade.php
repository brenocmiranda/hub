<table>
    <thead bgcolor="#f3f3f3">
    <tr>
        @if( array_search('created_at', $items) !== false)
            <th>Data</th>
        @endif
        @if( array_search('name', $items) !== false)
            <th>Nome</th>
        @endif
        @if( array_search('destinatarios', $items) !== false)
            <th>Destinatarios</th>
        @endif
        @if( array_search('integrations', $items) !== false)
            <th>Integrações</th>
        @endif
        @if( array_search('sheets', $items) !== false)
            <th>Sheets</th>
        @endif
        @if( array_search('keys', $items) !== false)
            <th>Chaves</th>
        @endif
        @if( array_search('companie', $items) !== false)
            <th>Empresas</th>
        @endif
        @if( array_search('active', $items) !== false)
            <th>Status</th>
        @endif
    </tr>
    </thead>
    <tbody>
        @foreach($buildings as $building)
            <tr>
                @if( array_search('created_at', $items) !== false)
                    <td align="center">{{ $building->created_at->format("d/m/Y H:i:s") }}</td>
                @endif
                @if( array_search('name', $items) !== false)
                    <td align="center">{{ $building->name }}</td>
                @endif
                @if( array_search('destinatarios', $items) !== false)
                    <td align="center">
                        @if($building->RelationDestinatarios->first())
                            @foreach($building->RelationDestinatarios as $destinatarios)
                                <?php $dest[] = $destinatarios->email ?>
                            @endforeach
                        @endif
                        {{ implode(', ', $dest) }}
                    </td>
                @endif
                @if( array_search('integrations', $items) !== false)
                    <td align="center">
                        @if($building->RelationIntegrations->first())
                            @foreach($building->RelationIntegrations as $integrations)
                                <?php $inte[] = $integrations->name ?>
                            @endforeach
                        @endif
                        {{ implode(', ', $inte) }}
                    </td>
                @endif
                @if( array_search('sheets', $items) !== false)
                    <td align="center">
                        @if($building->RelationSheets->first())
                            @foreach($building->RelationSheets as $sheets)
                                <?php $shet[] = $sheets->sheet ?>
                            @endforeach
                        @endif
                        {{ implode(', ', $shet) }}
                    </td>
                @endif
                @if( array_search('keys', $items) !== false)
                    <td align="center">
                        @if($building->RelationKeys->first())
                            @foreach($building->RelationKeys as $key)
                                <?php $ke[] = $key->value ?>
                            @endforeach
                        @endif
                        {{ implode(', ', $ke) }}
                    </td>
                @endif
                @if( array_search('companie', $items) !== false)
                    <td align="center">
                        @if($building->RelationPartners->first())
                            @foreach($building->RelationPartners as $partner)
                                <?php $part[] = $partner->RelationCompanies->name ?>
                            @endforeach
                        @endif
                        {{ implode(', ', $part) }}
                    </td>
                @endif
                @if( array_search('active', $items) !== false)
                    <td align="center">{{ $building->active ? 'Ativo' : 'Desativado' }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>