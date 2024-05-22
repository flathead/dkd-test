<?php
/**
 * Реализация простого файлового менеджера на PHP
 * 
 * Отображение изображений и папок по умолчанию для демонстрации заменено
 * на фильтрующий чекбокс с возможностью выбора типа отображения
 */


// Установите рабочую папку
$rootDir = realpath(__DIR__ . '/uploads'); // Определяем абсолютный путь к корневой папке uploads
$currentDir = isset($_GET['dir']) ? realpath($rootDir . '/' . $_GET['dir']) : $rootDir; // Определяем текущую директорию на основе параметра dir или устанавливаем корневую папку

// Предотвращаем выход за пределы рабочей папки
if ($currentDir === false || strpos($currentDir, $rootDir) !== 0) {
	$currentDir = $rootDir; // Если текущая директория не существует или находится вне корневой папки, устанавливаем корневую папку
}

// Получаем список файлов и папок
$files = array_diff(scandir($currentDir), ['..', '.']); // Сканируем текущую директорию и удаляем ссылки на родительскую и текущую директории

// Устанавливаем значение $showOnlyImages по умолчанию в true (включено)
$showOnlyImages = !isset($_GET['show_images']) || $_GET['show_images'] === 'on';

// Функция для проверки, является ли файл изображением
function isImage($file)
{
	$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION)); // Получаем расширение файла в нижнем регистре
	return in_array($ext, ['jpg', 'jpeg', 'png', 'gif']); // Проверяем, что расширение файла относится к изображениям
}

// Функция для проверки, является ли файл директорией
function isDirectory($file, $dir)
{
	return is_dir($dir . '/' . $file); // Проверяем, является ли файл директорией
}

// Функция для получения относительного пути между двумя директориями
function getRelativePath($from, $to)
{
	$from = str_replace('\\', '/', $from); // Заменяем обратные слеши на прямые слеши в пути
	$to = str_replace('\\', '/', $to); // Заменяем обратные слеши на прямые слеши в пути
	$from = explode('/', rtrim($from, '/')); // Разбиваем путь на массив частей, убирая завершающий слеш
	$to = explode('/', rtrim($to, '/')); // Разбиваем путь на массив частей, убирая завершающий слеш
	while (count($from) && count($to) && ($from[0] == $to[0])) {
		array_shift($from); // Удаляем совпадающие части пути из начала массива
		array_shift($to); // Удаляем совпадающие части пути из начала массива
	}
	return str_pad("", count($from) * 3, '../') . implode('/', $to); // Формируем относительный путь с помощью '../' и оставшихся частей пути
}
?>

<!DOCTYPE html>
<html lang="ru">

	<head>
		<meta charset="UTF-8">
		<title>Файловый менеджер</title> <!-- Устанавливаем заголовок страницы -->
		<style>
			.file-manager {
				width: 80%;
				margin: 0 auto;
				/* Центрируем блок на странице */
			}

			.file-item {
				margin: 5px 0;
				/* Отступы между элементами списка */
			}

			.directory {
				font-weight: bold;
				/* Жирный шрифт для директорий */
			}

			.image {
				color: blue;
				/* Синий цвет текста для изображений */
			}

			.image-preview {
				max-width: 200px;
				/* Ограничение максимальной ширины превью изображения */
				max-height: 200px;
				/* Ограничение максимальной высоты превью изображения */
			}

			ul {
				list-style: none;
			}
		</style>
	</head>

	<body>
		<div class="file-manager">
			<h1>Файловый менеджер</h1> <!-- Заголовок страницы -->
			<p>Текущий путь: /<?php echo htmlspecialchars(getRelativePath($rootDir, $currentDir)); ?></p>
			<!-- Выводим текущий путь -->
			<form method="GET">
				<!-- Поле для скрытого параметра текущей директории -->
				<input type="hidden" name="dir"
					value="<?php echo isset($_GET['dir']) ? htmlspecialchars($_GET['dir']) : ''; ?>">
				<label>
					<!-- Чекбокс для фильтрации только изображений -->
					<input type="checkbox" name="show_images" <?php echo $showOnlyImages ? 'checked' : ''; ?>>
					Показывать только изображения
				</label>
				<button type="submit">Применить</button> <!-- Кнопка для применения фильтра -->
			</form>
			<ul>
				<?php if ($currentDir !== $rootDir): ?> <!-- Если текущая директория не является корневой -->
					<li class="file-item directory">
						<!-- Ссылка для перехода на уровень выше -->
						<a
							href="?dir=<?php echo urlencode(getRelativePath($rootDir, dirname($currentDir))); ?>&show_images=<?php echo $showOnlyImages ? 'on' : 'off'; ?>"><i
								class="fa-solid fa-turn-up"></i> (Выше)</a>
					</li>
				<?php endif; ?>
				<?php foreach ($files as $file): ?> <!-- Перебираем все файлы и папки в текущей директории -->
					<?php if (isDirectory($file, $currentDir)): ?> <!-- Проверяем, является ли файл директорией -->
						<li class="file-item directory">
							<!-- Ссылка для перехода в поддиректорию -->
							<a
								href="?dir=<?php echo urlencode(getRelativePath($rootDir, $currentDir . '/' . $file)); ?>&show_images=<?php echo $showOnlyImages ? 'on' : 'off'; ?>"><i
									class="fa-solid fa-folder"></i> <?php echo $file; ?></a>
						</li>
					<?php elseif (!$showOnlyImages || (isImage($file) && $showOnlyImages)): ?>
						<!-- Если файл не изображение, либо фильтр установлен на отображение только изображений -->
						<li class="file-item <?php echo isImage($file) ? 'image' : ''; ?>">
							<!-- Добавляем класс "image" для изображений -->
							<?php if (isImage($file)): ?> <!-- Если файл является изображением -->
								<!-- Ссылка на изображение -->
								<a href="<?php echo htmlspecialchars('uploads/' . getRelativePath($rootDir, $currentDir) . '/' . $file); ?>"
									target="_blank"><?php echo $file; ?></a>
								<div>
									<!-- Превью изображения -->
									<img src="<?php echo htmlspecialchars('uploads/' . getRelativePath($rootDir, $currentDir) . '/' . $file); ?>"
										alt="<?php echo $file; ?>" class="image-preview">
								</div>
							<?php else: ?> <!-- Если файл не изображение -->
								<!-- Ссылка для скачивания файла -->
								<a href="<?php echo htmlspecialchars('uploads/' . getRelativePath($rootDir, $currentDir) . '/' . $file); ?>"
									download><?php echo $file; ?></a>
							<?php endif; ?>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>

		<!-- Font Awesome для иконок -->
		<script src="https://kit.fontawesome.com/9cd468f362.js" crossorigin="anonymous"></script>
	</body>

</html>