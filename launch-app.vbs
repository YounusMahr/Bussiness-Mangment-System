Set objShell = CreateObject("WScript.Shell")
Set objFSO = CreateObject("Scripting.FileSystemObject")

' Project directory
strProjectPath = "D:\Laravel Projects\Bussiness-MS"
strBatchFile = objFSO.BuildPath(strProjectPath, "launch-app.bat")

' Run the launcher batch file silently
objShell.Run """" & strBatchFile & """", 0, False

