<?php

namespace App\Filament\Resources\Movimentos\Pages;

use App\Filament\Resources\Movimentos\MovimentoResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Produto;
use Filament\Notifications\Notification;
use Filament\Support\Actions\Concerns\InteractsWithActions\Halt;


class CreateMovimento extends CreateRecord
{
    protected static string $resource = MovimentoResource::class;
    //hook - Verificar se há estoque suficiente
    protected function beforeCreate(): void
    {
        $data = $this->data;
        $produto = Produto::find($data['produto_id']);
        $quantidade = (int)$data['quantidade'];
        $tipo = $data['tipo'];

            if ($tipo === 'saida' && $quantidade> $produto->estoque){ //-> acessar propriedades e métodos
                Notification::make() //make: Cria uma notificação aparecendo em vermelho no topo
                    ->title('Estoque insuficiente')
                    ->body("Estoque de '{$produto->nome}' é de apenas {$produto->estoque} unidades.")
                    ->danger() //estilo vermelho
                    ->send(); //Enviar a notificação para o usuário no painel admin
                $this->halt(); //execute imediatamente
            }
    }

    //Criar Hook para diminuir o estoque do produto se der certo
    protected function afterCreate(): void
    {
        $movimento = $this->getRecord();
        $produto = $movimento->produto;
        if ($movimento->tipo === 'entrada'){
            $produto->increment('estoque', $movimento->quantidade);
        } else {
            $produto->decrement('estoque', $movimento->quantidade);
        }
    }
}
