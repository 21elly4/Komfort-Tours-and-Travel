<?php

namespace Komfort\App\Controllers;

use Komfort\Config\Logger;
use Komfort\App\Middleware\Auth;
use Komfort\App\Middleware\Csrf;

abstract class BaseController
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        
        $viewPath = __DIR__ . '/../Views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewPath)) {
            Log::error("View not found: {$view}");
            throw new \RuntimeException("View not found: {$view}");
        }

        require $viewPath;
    }

    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }

    protected function input(string $key, mixed $default = null): mixed
    {
        return $_REQUEST[$key] ?? $default;
    }

    protected function only(array $keys): array
    {
        return array_intersect_key($_REQUEST, array_flip($keys));
    }

    protected function except(array $keys): array
    {
        return array_diff_key($_REQUEST, array_flip($keys));
    }

    protected function validate(array $rules, array $data): array
    {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $ruleArray = is_array($rule) ? $rule : explode('|', $rule);
            $value = $data[$field] ?? null;
            
            foreach ($ruleArray as $singleRule) {
                if ($singleRule === 'required' && empty($value)) {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
                    break;
                }
                
                if ($singleRule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = 'Please enter a valid email address';
                    break;
                }
                
                if (str_starts_with($singleRule, 'min:')) {
                    $min = (int)substr($singleRule, 4);
                    if (strlen($value) < $min) {
                        $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " must be at least {$min} characters";
                        break;
                    }
                }
                
                if (str_starts_with($singleRule, 'max:')) {
                    $max = (int)substr($singleRule, 4);
                    if (strlen($value) > $max) {
                        $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " must not exceed {$max} characters";
                        break;
                    }
                }
                
                if ($singleRule === 'confirmed' && $value !== ($data[$field . '_confirmation'] ?? '')) {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' confirmation does not match';
                    break;
                }
            }
        }
        
        return $errors;
    }

    protected function sanitize(array $data): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = htmlspecialchars(strip_tags(trim($value)));
            } elseif (is_array($value)) {
                $sanitized[$key] = $this->sanitize($value);
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }

    protected function setFlash(string $type, string $message): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['flash'][$type] = $message;
    }

    protected function getFlash(string $type): ?string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $message = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        
        return $message;
    }

    protected function hasFlash(string $type): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['flash'][$type]);
    }
}
