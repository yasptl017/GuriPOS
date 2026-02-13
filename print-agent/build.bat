@echo off
echo ============================================
echo  Punjabi Paradise Print Agent - Build Tool
echo ============================================
echo.

:: Check Python is installed
python --version >nul 2>&1
if errorlevel 1 (
    echo ERROR: Python is not installed or not in PATH.
    echo Download from https://www.python.org/downloads/
    pause
    exit /b 1
)

echo [1/4] Installing dependencies...
pip install -r requirements.txt
if errorlevel 1 (
    echo ERROR: Failed to install requirements.
    pause
    exit /b 1
)

echo.
echo [2/4] Building executable...
pyinstaller ^
    --onefile ^
    --windowed ^
    --name "PunjabiParadisePrintAgent" ^
    --icon icon.ico ^
    --add-data "icon.ico;." ^
    --hidden-import win32print ^
    --hidden-import win32api ^
    --hidden-import pystray ^
    --hidden-import PIL ^
    --hidden-import PIL.Image ^
    --hidden-import PIL.ImageDraw ^
    agent.py

if errorlevel 1 (
    echo ERROR: PyInstaller build failed.
    pause
    exit /b 1
)

echo.
echo [3/4] Copying exe to dist folder...
echo Done.

echo.
echo [4/4] Build complete!
echo.
echo Output: dist\PunjabiParadisePrintAgent.exe
echo.
echo Copy that .exe to the restaurant PC and double-click to run.
echo.
pause
