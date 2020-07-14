<?php

return [
    'error' => [
        'forbidden' => 'Acesso negado',
        'unavailable' => 'Oops, parece que algo deu errado',
        'unauthorized' => 'Não autorizado',
        'notfound' => 'Não encontrado',
    ],

    'success' => [
        'created' => 'Cadastrado com sucesso',
        'updated' => 'Atualizado com sucesso',
        'removed' => 'Removido com sucesso',
    ],

    'users' => [
        'cant_delete_yourself' => 'Não é possível remover o usuário logado',
        'invalid_token' => 'Token inválido/expirado',
    ],

    'emails' => [
        'forgot_password' => [
            'greeting' => 'Olá',
            'subject' => 'Recuperação de senha',
            'action' => 'Atualizar senha',
            'content' => 'Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha para sua conta. Se você não solicitou uma redefinição de senha, nenhuma ação adicional será necessária.',
        ],
    ],

    'auth' => [
        'invalid_provider' => 'Provedor inválido'
    ]
];
