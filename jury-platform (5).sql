-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Дек 12 2025 г., 16:21
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `jury-platform`
--

-- --------------------------------------------------------

--
-- Структура таблицы `age_category`
--

CREATE TABLE `age_category` (
  `id` int(11) NOT NULL,
  `contest_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `min_age` int(11) DEFAULT NULL,
  `max_age` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `age_category`
--

INSERT INTO `age_category` (`id`, `contest_id`, `name`, `min_age`, `max_age`, `order`) VALUES
(1, 1, 'Дети 6-9 лет', 6, 9, 0),
(2, 1, 'Дети 10-13 лет', 10, 13, 0),
(3, 1, 'Юноши 14-17 лет', 14, 17, 0),
(4, 2, 'Молодежь 18-25 лет', 18, 25, 0),
(5, 2, 'Взрослые 26-35 лет', 26, 35, 0),
(6, 2, 'Профессионалы 36+ лет', 36, 100, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `application`
--

CREATE TABLE `application` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `contest_id` int(11) NOT NULL,
  `nomination_id` int(11) NOT NULL,
  `age_category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `patronymic` varchar(100) DEFAULT NULL,
  `work_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `institution` varchar(255) DEFAULT NULL,
  `leader` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `application`
--

INSERT INTO `application` (`id`, `user_id`, `contest_id`, `nomination_id`, `age_category_id`, `name`, `surname`, `patronymic`, `work_name`, `file_path`, `institution`, `leader`, `status`, `created_at`) VALUES
(1, 1, 1, 2, 3, 'Артем', 'Воинков', 'Александрович', 'Графический дизайн', 'uploads/applications/1765356565_ZVDvIClRcY.jpg', 'КПК', 'Пухов Алексей Александрович', 'completed', '2025-12-10 08:49:25'),
(2, 1, 2, 5, 6, 'Артем', 'Воинков', 'Александрович', 'Графический дизайн', 'uploads/applications/1765357054_As0R2lH_3g.jpg', 'КПК', 'Пухов Алексей Александрович', 'completed', '2025-12-10 08:57:34'),
(3, 2, 1, 1, 1, 'Иван', 'Макаров', 'Викторович', 'Картина', 'uploads/applications/1765360591_aXYMx8pf7X.jpg', 'КПК', 'Пухов Алексей Александрович', 'completed', '2025-12-10 09:56:31'),
(4, 2, 2, 4, 6, 'Иван', 'Макаров', 'Викторович', 'Фотография красивых мест', 'uploads/applications/1765360630_T9S7AglJ6d.jpg', 'КПК', 'Пухов Алексей Александрович', 'completed', '2025-12-10 09:57:10');

-- --------------------------------------------------------

--
-- Структура таблицы `contest`
--

CREATE TABLE `contest` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `contest`
--

INSERT INTO `contest` (`id`, `name`, `description`, `image`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, 'Весенний конкурс искусств', 'Ежегодный весенний конкурс для творческой молодежи', NULL, '2025-03-01', '2025-05-31', 1, '2025-12-05 16:11:24'),
(2, 'Осенний фестиваль талантов', 'Крупнейший фестиваль творчества и искусства', NULL, '2025-09-01', '2025-11-30', 1, '2025-12-05 16:11:24');

-- --------------------------------------------------------

--
-- Структура таблицы `contest_program`
--

CREATE TABLE `contest_program` (
  `id` int(11) NOT NULL,
  `contest_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `generated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `contest_result`
--

CREATE TABLE `contest_result` (
  `id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `final_score` decimal(5,2) DEFAULT NULL,
  `place` int(11) DEFAULT NULL,
  `award_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `contest_result`
--

INSERT INTO `contest_result` (`id`, `application_id`, `final_score`, `place`, `award_type`, `created_at`) VALUES
(1, 3, 30.00, NULL, NULL, '2025-12-10 13:43:48'),
(2, 4, 24.00, NULL, 'certificate', '2025-12-12 08:31:29'),
(3, 1, 18.00, NULL, 'certificate', '2025-12-12 08:45:37'),
(4, 2, 28.50, NULL, 'certificate', '2025-12-12 08:45:37');

-- --------------------------------------------------------

--
-- Структура таблицы `criteria`
--

CREATE TABLE `criteria` (
  `id` int(11) NOT NULL,
  `nomination_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `max_score` int(11) DEFAULT 10,
  `is_active` tinyint(1) DEFAULT 1,
  `order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `criteria`
--

INSERT INTO `criteria` (`id`, `nomination_id`, `name`, `description`, `max_score`, `is_active`, `order`, `created_at`) VALUES
(1, 1, 'Мастерство по направлению', NULL, 10, 1, 1, '2025-12-05 16:11:24'),
(2, 1, 'Артистизм / Раскрытие художественного образа', NULL, 10, 1, 2, '2025-12-05 16:11:24'),
(3, 1, 'Сценическая культура', NULL, 10, 1, 3, '2025-12-05 16:11:24'),
(4, 2, 'Мастерство по направлению', NULL, 10, 1, 1, '2025-12-10 11:23:14'),
(5, 2, 'Артистизм / Раскрытие художественного образа', NULL, 10, 1, 2, '2025-12-10 11:23:14'),
(6, 2, 'Сценическая культура', NULL, 10, 1, 3, '2025-12-10 11:23:14'),
(7, 3, 'Мастерство по направлению', NULL, 10, 1, 1, '2025-12-10 11:23:14'),
(8, 3, 'Артистизм / Раскрытие художественного образа', NULL, 10, 1, 2, '2025-12-10 11:23:14'),
(9, 3, 'Сценическая культура', NULL, 10, 1, 3, '2025-12-10 11:23:14'),
(10, 4, 'Мастерство по направлению', NULL, 10, 1, 1, '2025-12-10 11:23:14'),
(11, 4, 'Артистизм / Раскрытие художественного образа', NULL, 10, 1, 2, '2025-12-10 11:23:14'),
(12, 4, 'Сценическая культура', NULL, 10, 1, 3, '2025-12-10 11:23:14'),
(13, 5, 'Мастерство по направлению', NULL, 10, 1, 1, '2025-12-10 11:23:14'),
(14, 5, 'Артистизм / Раскрытие художественного образа', NULL, 10, 1, 2, '2025-12-10 11:23:14'),
(15, 5, 'Сценическая культура', NULL, 10, 1, 3, '2025-12-10 11:23:14'),
(16, 6, 'Мастерство по направлению', NULL, 10, 1, 1, '2025-12-10 11:23:14'),
(17, 6, 'Артистизм / Раскрытие художественного образа', NULL, 10, 1, 2, '2025-12-10 11:23:14'),
(18, 6, 'Сценическая культура', NULL, 10, 1, 3, '2025-12-10 11:23:14');

-- --------------------------------------------------------

--
-- Структура таблицы `criteria_nomination`
--

CREATE TABLE `criteria_nomination` (
  `id` int(11) NOT NULL,
  `criteria_id` int(11) NOT NULL,
  `nomination_id` int(11) NOT NULL,
  `weight` int(11) DEFAULT 1 COMMENT 'Вес критерия',
  `order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `evaluation`
--

CREATE TABLE `evaluation` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `expert_id` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'draft',
  `total_score` decimal(5,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `score` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `evaluation`
--

INSERT INTO `evaluation` (`id`, `application_id`, `expert_id`, `status`, `total_score`, `notes`, `created_at`, `updated_at`, `score`) VALUES
(1, 4, 1, 'completed', 24.00, 'Хорошая работа', '2025-12-10 11:23:27', '2025-12-12 11:44:41', 24.00),
(2, 3, 1, 'completed', 30.00, 'Отличная работа', '2025-12-10 13:05:15', '2025-12-12 11:44:41', 30.00),
(3, 1, 1, 'completed', 15.00, '', '2025-12-11 17:07:43', '2025-12-12 11:44:41', 15.00),
(4, 2, 1, 'completed', 27.00, 'Весьма хорошая работа', '2025-12-12 11:19:06', '2025-12-12 11:44:41', 27.00),
(5, 2, 2, 'completed', 30.00, '', '2025-12-12 12:20:44', '2025-12-12 12:20:44', 30.00),
(6, 1, 2, 'completed', 21.00, '', '2025-12-12 12:21:58', '2025-12-12 12:21:58', 21.00);

--
-- Триггеры `evaluation`
--
DELIMITER $$
CREATE TRIGGER `after_evaluation_insert` AFTER INSERT ON `evaluation` FOR EACH ROW BEGIN
    DECLARE avg_score DECIMAL(5,2);
    
    -- Получаем средний балл по всем завершенным оценкам (используем total_score)
    SELECT AVG(total_score) INTO avg_score
    FROM evaluation
    WHERE application_id = NEW.application_id 
    AND status = 'completed';
    
    -- Обновляем или создаем запись в contest_result
    IF EXISTS (SELECT 1 FROM contest_result WHERE application_id = NEW.application_id) THEN
        UPDATE contest_result 
        SET final_score = avg_score
        WHERE application_id = NEW.application_id;
    ELSE
        INSERT INTO contest_result (application_id, final_score, created_at)
        VALUES (NEW.application_id, COALESCE(avg_score, 0), NOW());
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sync_evaluation_score` BEFORE INSERT ON `evaluation` FOR EACH ROW BEGIN
    -- Если при вставке задан total_score, но не задан score, скопировать
    IF NEW.total_score IS NOT NULL AND NEW.score IS NULL THEN
        SET NEW.score = NEW.total_score;
    -- Если при вставке задан score, но не задан total_score, скопировать
    ELSEIF NEW.score IS NOT NULL AND NEW.total_score IS NULL THEN
        SET NEW.total_score = NEW.score;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sync_evaluation_score_update` BEFORE UPDATE ON `evaluation` FOR EACH ROW BEGIN
    -- При обновлении синхронизируем поля
    IF NEW.total_score IS NOT NULL AND (NEW.score IS NULL OR NEW.score != NEW.total_score) THEN
        SET NEW.score = NEW.total_score;
    ELSEIF NEW.score IS NOT NULL AND (NEW.total_score IS NULL OR NEW.total_score != NEW.score) THEN
        SET NEW.total_score = NEW.score;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `evaluation_score`
--

CREATE TABLE `evaluation_score` (
  `id` int(11) NOT NULL,
  `evaluation_id` int(11) NOT NULL,
  `criteria_id` int(11) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `evaluation_score`
--

INSERT INTO `evaluation_score` (`id`, `evaluation_id`, `criteria_id`, `score`) VALUES
(10, 2, 1, 10),
(11, 2, 2, 10),
(12, 2, 3, 10),
(16, 3, 4, 5),
(17, 3, 5, 5),
(18, 3, 6, 5),
(22, 1, 10, 10),
(23, 1, 11, 8),
(24, 1, 12, 6),
(28, 4, 13, 9),
(29, 4, 14, 8),
(30, 4, 15, 10),
(31, 5, 13, 10),
(32, 5, 14, 10),
(33, 5, 15, 10),
(34, 6, 4, 7),
(35, 6, 5, 8),
(36, 6, 6, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `evaluation_sheet`
--

CREATE TABLE `evaluation_sheet` (
  `id` int(11) NOT NULL,
  `contest_id` int(11) DEFAULT NULL,
  `nomination_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `generated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `expert_assignment`
--

CREATE TABLE `expert_assignment` (
  `id` int(11) NOT NULL,
  `expert_id` int(11) NOT NULL,
  `contest_id` int(11) NOT NULL,
  `nomination_id` int(11) NOT NULL,
  `age_category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `expert_assignment`
--

INSERT INTO `expert_assignment` (`id`, `expert_id`, `contest_id`, `nomination_id`, `age_category_id`, `created_at`) VALUES
(1, 2, 1, 2, 3, '2025-12-10 09:54:48'),
(2, 2, 2, 5, 6, '2025-12-10 09:54:48'),
(3, 1, 1, 1, 1, '2025-12-10 10:00:05'),
(4, 1, 2, 4, 6, '2025-12-10 10:00:05');

-- --------------------------------------------------------

--
-- Структура таблицы `generated_document`
--

CREATE TABLE `generated_document` (
  `id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `document_type` enum('diploma','certificate') DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `generated_document`
--

INSERT INTO `generated_document` (`id`, `application_id`, `document_type`, `file_path`, `generated_at`) VALUES
(1, 1, 'diploma', 'uploads/diplomas/diplomas_1_1765548784.html', '2025-12-12 14:13:04'),
(2, 1, 'certificate', 'uploads/certificates/certificates_1_1765548787.html', '2025-12-12 14:13:07'),
(3, 1, '', 'uploads/albums/albums_1_1765549531.html', '2025-12-12 14:25:31'),
(4, 2, 'diploma', 'uploads/diplomas/diplomas_2_1765549615.html', '2025-12-12 14:26:55'),
(5, 2, 'certificate', 'uploads/certificates/certificates_2_1765549618.html', '2025-12-12 14:26:58'),
(6, 2, '', 'uploads/albums/albums_2_1765549620.html', '2025-12-12 14:27:00'),
(7, 3, 'diploma', 'uploads/diplomas/diplomas_3_1765549628.html', '2025-12-12 14:27:08'),
(8, 3, 'certificate', 'uploads/certificates/certificates_3_1765549630.html', '2025-12-12 14:27:10'),
(9, 3, '', 'uploads/albums/albums_3_1765549632.html', '2025-12-12 14:27:12'),
(10, 4, 'diploma', 'uploads/diplomas/diplomas_4_1765549638.html', '2025-12-12 14:27:18'),
(11, 4, 'certificate', 'uploads/certificates/certificates_4_1765549640.html', '2025-12-12 14:27:20'),
(12, 4, '', 'uploads/albums/albums_4_1765549642.html', '2025-12-12 14:27:22');

-- --------------------------------------------------------

--
-- Структура таблицы `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `nomination`
--

CREATE TABLE `nomination` (
  `id` int(11) NOT NULL,
  `contest_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `max_participants` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `nomination`
--

INSERT INTO `nomination` (`id`, `contest_id`, `name`, `description`, `max_participants`, `order`) VALUES
(1, 1, 'Живопись', NULL, NULL, 0),
(2, 1, 'Графика', NULL, NULL, 0),
(3, 1, 'Скульптура', NULL, NULL, 0),
(4, 2, 'Фотография', NULL, NULL, 0),
(5, 2, 'Дизайн', NULL, NULL, 0),
(6, 2, 'Декоративно-прикладное искусство', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(20) DEFAULT 'new',
  `notification_type` varchar(255) DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `notification`
--

INSERT INTO `notification` (`id`, `user_id`, `title`, `message`, `status`, `notification_type`, `metadata`, `created_at`) VALUES
(1, 1, 'Добро пожаловать!', 'Вы успешно зарегистрировались в системе конкурсов. Теперь вы можете подавать заявки на участие.', 'new', NULL, NULL, '2025-12-10 07:59:22'),
(2, 1, 'Заявка подана', 'Ваша заявка \'Графический дизайн\' успешно подана на конкурс \'Весенний конкурс искусств\'. Статус: Новая', 'new', NULL, NULL, '2025-12-10 08:49:25'),
(3, 1, 'Заявка подана', 'Ваша заявка \'Графический дизайн\' успешно подана на конкурс \'Осенний фестиваль талантов\'. Статус: Новая', 'new', NULL, NULL, '2025-12-10 08:57:34'),
(4, 2, 'Добро пожаловать!', 'Вы успешно зарегистрировались в системе конкурсов. Теперь вы можете подавать заявки на участие.', 'new', NULL, NULL, '2025-12-10 09:43:29'),
(5, 2, 'Заявка подана', 'Ваша заявка \'Картина\' успешно подана на конкурс \'Весенний конкурс искусств\'. Статус: Новая', 'new', NULL, NULL, '2025-12-10 09:56:31'),
(6, 2, 'Заявка подана', 'Ваша заявка \'Фотография красивых мест\' успешно подана на конкурс \'Осенний фестиваль талантов\'. Статус: Новая', 'new', NULL, NULL, '2025-12-10 09:57:10'),
(7, 2, 'Аккаунт заблокирован', 'Ваш аккаунт был заблокирован администратором. Вы не можете войти в систему.', 'new', NULL, NULL, '2025-12-10 10:11:17'),
(8, 2, 'Аккаунт разблокирован', 'Ваш аккаунт был разблокирован администратором. Теперь вы можете войти в систему.', 'new', NULL, NULL, '2025-12-10 10:12:37'),
(9, 2, 'Заявка оценена экспертом', 'Ваша заявка \'Фотография красивых мест\' была оценена экспертом. Общий балл: 24', 'new', NULL, NULL, '2025-12-10 11:26:41'),
(10, 2, 'Заявка оценена экспертом', 'Ваша заявка \'Фотография красивых мест\' была оценена экспертом. Общий балл: 24', 'new', NULL, NULL, '2025-12-10 13:19:32'),
(11, 2, 'Статус заявки изменен', 'Статус вашей заявки \'Фотография красивых мест\' изменен с \'Новая\' на \'На проверке\'', 'new', NULL, NULL, '2025-12-10 13:41:21'),
(12, 1, 'Все эксперты завершили оценку', 'Все эксперты завершили оценку заявки \'Картина\'. Заявка переведена в статус \'Оценена\'.', 'new', NULL, NULL, '2025-12-10 13:43:48'),
(13, 2, 'Заявка оценена экспертом', 'Ваша заявка \'Картина\' была оценена экспертом. Общий балл: 30', 'new', NULL, NULL, '2025-12-10 13:43:48'),
(14, 1, 'Оценка сброшена', 'Ваша оценка заявки \'Картина\' была сброшена администратором', 'new', NULL, NULL, '2025-12-10 13:44:05'),
(16, 1, 'Заявка оценена экспертом', 'Ваша заявка \'Графический дизайн\' была оценена экспертом. Общий балл: 15', 'new', NULL, NULL, '2025-12-11 17:07:43'),
(17, 2, 'Заявка оценена экспертом', 'Ваша заявка \'Фотография красивых мест\' была оценена экспертом. Общий балл: 24', 'new', NULL, NULL, '2025-12-11 17:08:12'),
(18, 1, 'Все эксперты завершили оценку', 'Все эксперты завершили оценку заявки \'Картина\'. Заявка переведена в статус \'Оценена\'.', 'new', NULL, NULL, '2025-12-11 17:09:05'),
(19, 2, 'Заявка оценена экспертом', 'Ваша заявка \'Картина\' была оценена экспертом. Общий балл: 30', 'new', NULL, NULL, '2025-12-11 17:09:05'),
(20, 2, 'Статус заявки изменен', 'Статус вашей заявки \'Фотография красивых мест\' изменен с \'На проверке\' на \'Оценена\'', 'new', NULL, NULL, '2025-12-12 06:25:21'),
(21, 1, 'Статус заявки изменен', 'Статус вашей заявки \'Графический дизайн\' изменен с \'Новая\' на \'На проверке\'', 'new', NULL, NULL, '2025-12-12 07:05:49'),
(22, 1, 'Статус заявки изменен', 'Статус вашей заявки \'Графический дизайн\' изменен с \'Новая\' на \'На проверке\'', 'new', NULL, NULL, '2025-12-12 07:05:53'),
(23, 2, 'Заявка оценена экспертом', 'Ваша заявка \'Фотография красивых мест\' была оценена экспертом. Общий балл: 24', 'new', NULL, NULL, '2025-12-12 07:33:44'),
(24, 1, 'Заявка оценена экспертом', 'Ваша заявка \'Графический дизайн\' была оценена экспертом. Общий балл: 27', 'new', NULL, NULL, '2025-12-12 11:19:06'),
(25, 1, 'Заявка оценена экспертом', 'Ваша заявка \'Графический дизайн\' была оценена экспертом. Общий балл: 27', 'new', NULL, NULL, '2025-12-12 11:44:48'),
(26, 1, 'Заявка оценена экспертом', 'Ваша заявка \'Графический дизайн\' была оценена экспертом. Общий балл: 30', 'new', NULL, NULL, '2025-12-12 12:20:44'),
(27, 1, 'Заявка оценена экспертом', 'Ваша заявка \'Графический дизайн\' была оценена экспертом. Общий балл: 21', 'new', NULL, NULL, '2025-12-12 12:21:58'),
(28, 2, 'Документ сгенерирован', 'Ваш  по заявке \'Картина\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 12:56:09'),
(29, 1, 'Документ сгенерирован', 'Ваш диплом по заявке \'Графический дизайн\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 13:22:12'),
(30, 2, 'Документ сгенерирован', 'Ваш диплом по заявке \'Фотография красивых мест\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 13:39:13'),
(31, 1, 'Документ сгенерирован', 'Ваш диплом по заявке \'Графический дизайн\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 13:51:25'),
(32, 1, 'Документ сгенерирован', 'Ваш диплом по заявке \'Графический дизайн\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 13:55:35'),
(33, 2, 'Документ сгенерирован', 'Ваш диплом по заявке \'Картина\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 13:55:43'),
(34, 2, 'Документ сгенерирован', 'Ваш диплом по заявке \'Фотография красивых мест\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 13:55:49'),
(35, 1, 'Документ сгенерирован', 'Ваш диплом по заявке \'Графический дизайн\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 14:08:01'),
(36, 1, 'Документ сгенерирован', 'Ваш сертификат по заявке \'Графический дизайн\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 14:08:04'),
(37, 1, 'Документ сгенерирован', 'Ваш диплом по заявке \'Графический дизайн\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 14:13:04'),
(38, 1, 'Документ сгенерирован', 'Ваш сертификат по заявке \'Графический дизайн\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 14:13:07'),
(39, 1, 'Документ сгенерирован', 'Ваш альбом по заявке \'Графический дизайн\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 14:25:31'),
(40, 1, 'Документ сгенерирован', 'Ваш диплом по заявке \'Графический дизайн\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 14:26:55'),
(41, 1, 'Документ сгенерирован', 'Ваш сертификат по заявке \'Графический дизайн\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 14:26:58'),
(42, 1, 'Документ сгенерирован', 'Ваш альбом по заявке \'Графический дизайн\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 14:27:00'),
(43, 2, 'Документ сгенерирован', 'Ваш диплом по заявке \'Картина\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 14:27:08'),
(44, 2, 'Документ сгенерирован', 'Ваш сертификат по заявке \'Картина\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 14:27:10'),
(45, 2, 'Документ сгенерирован', 'Ваш альбом по заявке \'Картина\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 14:27:12'),
(46, 2, 'Документ сгенерирован', 'Ваш диплом по заявке \'Фотография красивых мест\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 14:27:18'),
(47, 2, 'Документ сгенерирован', 'Ваш сертификат по заявке \'Фотография красивых мест\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 14:27:20'),
(48, 2, 'Документ сгенерирован', 'Ваш альбом по заявке \'Фотография красивых мест\' готов к скачиванию.', 'new', NULL, NULL, '2025-12-12 14:27:22');

-- --------------------------------------------------------

--
-- Структура таблицы `report_template`
--

CREATE TABLE `report_template` (
  `id` int(11) NOT NULL,
  `contest_id` int(11) DEFAULT NULL,
  `type` enum('program','evaluation_sheet','diploma','certificate','album') DEFAULT NULL,
  `template_file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `report_template`
--

INSERT INTO `report_template` (`id`, `contest_id`, `type`, `template_file`, `created_at`) VALUES
(1, NULL, 'diploma', 'diploma_template.html', '2025-12-12 12:47:38'),
(2, NULL, 'certificate', 'certificate_template.html', '2025-12-12 12:47:38'),
(3, NULL, 'program', 'program_template.html', '2025-12-12 12:47:38'),
(4, NULL, 'evaluation_sheet', 'evaluation_sheet_template.html', '2025-12-12 12:47:38'),
(5, NULL, 'album', 'album_template.html', '2025-12-12 13:56:41');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `patronymic` varchar(100) DEFAULT NULL,
  `login` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_repeat` varchar(255) NOT NULL,
  `auth_key` varchar(32) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rules` tinyint(1) DEFAULT 0,
  `is_blocked` tinyint(1) DEFAULT 0,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `is_expert` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `name`, `surname`, `patronymic`, `login`, `email`, `password`, `password_repeat`, `auth_key`, `is_admin`, `created_at`, `rules`, `is_blocked`, `password_reset_token`, `is_expert`) VALUES
(1, 'Артем', 'Воинков', 'Александрович', 'Admin', 'artem@mail.ru', '$2y$13$ZO9r6lk6xMygm0C43PIimuAt5KANJefhJqfUwmOZXuIVfxdFznR2.', '', 'B9X0bm_O4yeYLHYVPWJRc5aIKdLG4sZH', 1, '2025-12-10 07:59:22', 1, 0, NULL, 1),
(2, 'Иван ', 'Макаров', 'Викторович', 'Neu3BecTHo', 'atmoteam@mail.ru', '$2y$13$.AdmA3WeLFGQQv0MZ72Li.w3AGLfkAJhka8dElTqH1tOoQgWL9MG6', '', '8bI5e7JiCCqdUw4j8kPal4f11XnBcbbs', 0, '2025-12-10 09:43:29', 1, 0, NULL, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `age_category`
--
ALTER TABLE `age_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-age_category-contest_id` (`contest_id`);

--
-- Индексы таблицы `application`
--
ALTER TABLE `application`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-application-user_id` (`user_id`),
  ADD KEY `fk-application-contest_id` (`contest_id`),
  ADD KEY `fk-application-nomination_id` (`nomination_id`),
  ADD KEY `fk-application-age_category_id` (`age_category_id`);

--
-- Индексы таблицы `contest`
--
ALTER TABLE `contest`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `contest_program`
--
ALTER TABLE `contest_program`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contest_id` (`contest_id`);

--
-- Индексы таблицы `contest_result`
--
ALTER TABLE `contest_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Индексы таблицы `criteria`
--
ALTER TABLE `criteria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-criteria-nomination_id` (`nomination_id`);

--
-- Индексы таблицы `criteria_nomination`
--
ALTER TABLE `criteria_nomination`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `evaluation`
--
ALTER TABLE `evaluation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-evaluation-application_id` (`application_id`),
  ADD KEY `fk-evaluation-expert_id` (`expert_id`);

--
-- Индексы таблицы `evaluation_score`
--
ALTER TABLE `evaluation_score`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-evaluation_score-evaluation_id` (`evaluation_id`),
  ADD KEY `fk-evaluation_score-criteria_id` (`criteria_id`);

--
-- Индексы таблицы `evaluation_sheet`
--
ALTER TABLE `evaluation_sheet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contest_id` (`contest_id`),
  ADD KEY `nomination_id` (`nomination_id`);

--
-- Индексы таблицы `expert_assignment`
--
ALTER TABLE `expert_assignment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-expert_assignment-expert_id` (`expert_id`),
  ADD KEY `fk-expert_assignment-contest_id` (`contest_id`),
  ADD KEY `fk-expert_assignment-nomination_id` (`nomination_id`),
  ADD KEY `fk-expert_assignment-age_category_id` (`age_category_id`);

--
-- Индексы таблицы `generated_document`
--
ALTER TABLE `generated_document`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Индексы таблицы `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `nomination`
--
ALTER TABLE `nomination`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-nomination-contest_id` (`contest_id`);

--
-- Индексы таблицы `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-notification-user_id` (`user_id`);

--
-- Индексы таблицы `report_template`
--
ALTER TABLE `report_template`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contest_id` (`contest_id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `age_category`
--
ALTER TABLE `age_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `application`
--
ALTER TABLE `application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `contest`
--
ALTER TABLE `contest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `contest_program`
--
ALTER TABLE `contest_program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `contest_result`
--
ALTER TABLE `contest_result`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `criteria`
--
ALTER TABLE `criteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `criteria_nomination`
--
ALTER TABLE `criteria_nomination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `evaluation`
--
ALTER TABLE `evaluation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `evaluation_score`
--
ALTER TABLE `evaluation_score`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT для таблицы `evaluation_sheet`
--
ALTER TABLE `evaluation_sheet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `expert_assignment`
--
ALTER TABLE `expert_assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `generated_document`
--
ALTER TABLE `generated_document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `nomination`
--
ALTER TABLE `nomination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT для таблицы `report_template`
--
ALTER TABLE `report_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `age_category`
--
ALTER TABLE `age_category`
  ADD CONSTRAINT `fk-age_category-contest_id` FOREIGN KEY (`contest_id`) REFERENCES `contest` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `application`
--
ALTER TABLE `application`
  ADD CONSTRAINT `fk-application-age_category_id` FOREIGN KEY (`age_category_id`) REFERENCES `age_category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-application-contest_id` FOREIGN KEY (`contest_id`) REFERENCES `contest` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-application-nomination_id` FOREIGN KEY (`nomination_id`) REFERENCES `nomination` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-application-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `contest_program`
--
ALTER TABLE `contest_program`
  ADD CONSTRAINT `contest_program_ibfk_1` FOREIGN KEY (`contest_id`) REFERENCES `contest` (`id`);

--
-- Ограничения внешнего ключа таблицы `contest_result`
--
ALTER TABLE `contest_result`
  ADD CONSTRAINT `contest_result_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `application` (`id`);

--
-- Ограничения внешнего ключа таблицы `criteria`
--
ALTER TABLE `criteria`
  ADD CONSTRAINT `fk-criteria-nomination_id` FOREIGN KEY (`nomination_id`) REFERENCES `nomination` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `evaluation`
--
ALTER TABLE `evaluation`
  ADD CONSTRAINT `fk-evaluation-application_id` FOREIGN KEY (`application_id`) REFERENCES `application` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-evaluation-expert_id` FOREIGN KEY (`expert_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `evaluation_score`
--
ALTER TABLE `evaluation_score`
  ADD CONSTRAINT `fk-evaluation_score-criteria_id` FOREIGN KEY (`criteria_id`) REFERENCES `criteria` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-evaluation_score-evaluation_id` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `evaluation_sheet`
--
ALTER TABLE `evaluation_sheet`
  ADD CONSTRAINT `evaluation_sheet_ibfk_1` FOREIGN KEY (`contest_id`) REFERENCES `contest` (`id`),
  ADD CONSTRAINT `evaluation_sheet_ibfk_2` FOREIGN KEY (`nomination_id`) REFERENCES `nomination` (`id`);

--
-- Ограничения внешнего ключа таблицы `expert_assignment`
--
ALTER TABLE `expert_assignment`
  ADD CONSTRAINT `fk-expert_assignment-age_category_id` FOREIGN KEY (`age_category_id`) REFERENCES `age_category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-expert_assignment-contest_id` FOREIGN KEY (`contest_id`) REFERENCES `contest` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-expert_assignment-expert_id` FOREIGN KEY (`expert_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-expert_assignment-nomination_id` FOREIGN KEY (`nomination_id`) REFERENCES `nomination` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `generated_document`
--
ALTER TABLE `generated_document`
  ADD CONSTRAINT `generated_document_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `application` (`id`);

--
-- Ограничения внешнего ключа таблицы `nomination`
--
ALTER TABLE `nomination`
  ADD CONSTRAINT `fk-nomination-contest_id` FOREIGN KEY (`contest_id`) REFERENCES `contest` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `fk-notification-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `report_template`
--
ALTER TABLE `report_template`
  ADD CONSTRAINT `report_template_ibfk_1` FOREIGN KEY (`contest_id`) REFERENCES `contest` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
