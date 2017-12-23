<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 18/12/2017
 * Time: 00:02
 */

use Hcode\Builder\PageBuilder;
use Hcode\Middleware\AuthMiddleware;
use Hcode\Model\Security\Authenticator;
use Hcode\Model\Security\PasswordHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Hcode\Middleware\LoggedMiddleware;

$app->group("/admin", function () use ($app) {
    $app->group('', function () use ($app) {

        $app->get('', function () {

            (new PageBuilder())->withHeader()
                               ->withFooter()
                               ->withTpl('index')
                               ->build(true);
        }
        );

        require_once("admin-users.php");
        require_once("admin-categories.php");
        require_once("admin-products.php");
    }

    )
        ->add(new AuthMiddleware());

    $app->get("/login", function () {
        $pageBuilder = new PageBuilder();
        $pageBuilder->withTpl("login")
                    ->withData(['error' => $this->flash->getFirstMessage('error')])
                    ->build(true);
    }
    )
        ->add(new LoggedMiddleware());

    $app->post('/login', function () {
        try {
            Authenticator::login($_POST['login'], $_POST['password']);
            header("Location: /admin");
            exit;
        } catch (Exception $e) {
            $this->flash->addMessage('error', $e->getMessage());
            header('Location: /admin/login');
            exit;
        }


    }
    );

    $app->get('/logout', function () {
        Authenticator::logout();
        header("Location: /admin/login");
        exit;
    }
    );

    $app->get('/forgot', function () {

        (new PageBuilder())->withTpl('forgot')
                           ->build(true);

    }
    );

    $app->get('/forgot/sent', function () {
        (new PageBuilder())->withTpl('forgot-sent')
                           ->build(true);
    }
    );

    $app->post('/forgot', function () {
        PasswordHelper::createRecovery($_POST['email']);
        header("Location: /admin/forgot/sent");
        exit;
    }
    );

    $app->get('/forgot/reset', function () {

        $data = PasswordHelper::validRecovery($_GET['code']);

        if (isset($data)) {
            (new PageBuilder())->withData(['name' => $data['desperson'], 'code' => $_GET['code']])
                               ->withTpl('forgot-reset')
                               ->build(true);

        } else {
            throw new \Exception("Não foi possivel recuperar a senha");
        }

    }
    );

    $app->post('/forgot/reset', function () {
        $data = PasswordHelper::validRecovery($_POST['code']);

        if (isset($data)) {
            PasswordHelper::recovery($data['idrecovery'], $data['iduser'], $_POST['password']);
            (new PageBuilder())->withTpl("forgot-reset-success")
                               ->build(true);
        }
    }
    );
}
);

