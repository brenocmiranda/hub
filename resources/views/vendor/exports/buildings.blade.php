<table>
    <thead bgcolor="#f3f3f3">
    <tr>
        @if( array_search('created_at', $items) !== false)
            <th align="center">Data</th>
        @endif
        @if( array_search('name', $items) !== false)
            <th align="center">Nome</th>
        @endif
        @if( array_search('destinatarios', $items) !== false)
            <th align="center">Destinatarios</th>
        @endif
        @if( array_search('integrationsBuild', $items) !== false)
            <th align="center">Integrações</th>
        @endif
        @if( array_search('sheets', $items) !== false)
            <th align="center">Sheets</th>
        @endif
        @if( array_search('keys', $items) !== false)
            <th align="center">Chaves</th>
        @endif
        @if( array_search('company', $items) !== false)
            <th align="center">Empresas</th>
        @endif
        @if( array_search('active', $items) !== false)
            <th align="center">Status</th>
        @endif
    </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
            <tr>
                @if( array_search('created_at', $items) !== false)
                    <td align="center">{{ $product->created_at->format("d/m/Y H:i:s") }}</td>
                @endif
                @if( array_search('name', $items) !== false)
                    <td align="center">{{ $product->name }}</td>
                @endif
                @if( array_search('destinatarios', $items) !== false)
                    <?php $dest = array(); ?>
                    <td align="center">
                        @if($product->RelationDestinatarios->first())
                            @foreach($product->RelationDestinatarios as $destinatarios)
                                <?php $dest[] = $destinatarios->email ?>
                            @endforeach
                        @endif
                        {{ implode(', ', $dest) }}
                    </td>
                @endif
                @if( array_search('integrationsBuild', $items) !== false)
                    <?php $inte = array(); ?>
                    <td align="center">
                        @if($product->RelationIntegrations->first())
                            @foreach($product->RelationIntegrations as $integrations)
                                <?php $inte[] = $integrations->name ?>
                            @endforeach
                        @endif
                        {{ implode(', ', $inte) }}
                    </td>
                @endif
                @if( array_search('sheets', $items) !== false)
                    <?php $shet = array(); ?>
                    <td align="center">
                        @if($product->RelationSheets->first())
                            @foreach($product->RelationSheets as $sheets)
                                <?php $shet[] = $sheets->sheet ?>
                            @endforeach
                        @endif
                        {{ implode(', ', $shet) }}
                    </td>
                @endif
                @if( array_search('keys', $items) !== false)
                    <?php $ke = array(); ?>
                    <td align="center">
                        @if($product->RelationKeys->first())
                            @foreach($product->RelationKeys as $key)
                                <?php $ke[] = $key->value ?>
                            @endforeach
                        @endif
                        {{ implode(', ', $ke) }}
                    </td>
                @endif
                @if( array_search('company', $items) !== false)
                    <?php $part = array(); ?>
                    <td align="center">
                        @if($product->RelationPartners->first())
                            @foreach($product->RelationPartners as $partner)
                                <?php $part[] = $partner->RelationCompanies->name ?>
                            @endforeach
                        @endif
                        {{ implode(', ', $part) }}
                    </td>
                @endif
                @if( array_search('active', $items) !== false)
                    <td align="center">{{ $product->active ? 'Ativo' : 'Desativado' }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>