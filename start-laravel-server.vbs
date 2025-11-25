' Laravel Server Auto-Start Script (Hidden Window)
' This VBScript runs the batch file in a hidden window
Set WshShell = CreateObject("WScript.Shell")
WshShell.Run "cmd /c """ & CreateObject("Scripting.FileSystemObject").GetParentFolderName(WScript.ScriptFullName) & "\start-laravel-server.bat""", 0, False
Set WshShell = Nothing

