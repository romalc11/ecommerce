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
use Hcode\Middleware\LoggedMiddleware;
use Psr\Http\Message\ServerRequestInterface;

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
        ->add(new AuthMiddleware(true));

    $app->get("/login", function (ServerRequestInterface $request) {
        $error = $request->getAttribute('error');

        if (isset($error)) {
            $this->flash->addMessage('error', $error);
        }

        $pageBuilder = new PageBuilder();
        $pageBuilder->withTpl("login")
                    ->withData(['error' => $this->flash->getFirstMessage('error')])
                    ->build(true);
    }
    )
        ->add(new LoggedMiddleware(true));

    $app->post('/login', function () {
        $distUrl = 'Location: ';
        try {
            Authenticator::login($_POST['login'], $_POST['password'], true);
            $distUrl .= '/admin';
        } catch (Exception $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $distUrl .= '/login';
        } finally {
            header($distUrl);
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

