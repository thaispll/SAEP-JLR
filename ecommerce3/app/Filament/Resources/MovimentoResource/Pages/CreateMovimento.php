<?php

namespace App\Filament\Resources\MovimentoResource\Pages;

use App\Filament\Resources\MovimentoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Produto;
use Filament\Notifications\Notification;
class CreateMovimento extends CreateRecord
{
    protected static string $resource = MovimentoResource::class;

    //antes de criar 
    protected function beforeCreate(): void
    {
        $data=$this->data;
        $produto = Produto::find($data['produto_id']);
        $quantidade = (int)$data['quantidade'];
        $tipo = $data['tipo'];

        if (!$produto) {
            Notification::make()
                ->title('Produto não encontrado')
                ->body('Selecione um produto válido.')
                ->danger()
                ->send();
            
            $this->halt();
        }
        if ($tipo === 'saida' && $quantidade >$produto->estoque){
            Notification::make() //make: cria notificação que vai aparecer em vermelho no topo
                ->title('Estoque insuficiente')
                ->body("Estoque de '{$produto->nome}' é de apenas {$produto->estoque} unidades")
                ->danger()
                ->send();//envia a notificação para o usuário no painel admin
            $this->halt(); //execute imediatamente
        }
    }
    //depois de criar 
    protected function afterCreate(): void
    {
        $movimento = $this->getRecord();
        $produto = $movimento->produto;

        if ( $movimento->tipo === 'entrada'){
            $produto->increment('estoque', $movimento->quantidade);
        } else {
            $produto->decrement('estoque', $movimento->quantidade);
        }
    }
}
