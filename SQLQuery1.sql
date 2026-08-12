CREATE TRIGGER trg_PreventDelete_Employees_ById
ON dbo.employees
INSTEAD OF DELETE
AS
BEGIN
  SET NOCOUNT ON;

  -- لو تحاول حذف السجل ذو id = 5، امنع العملية
  IF EXISTS (SELECT 1 FROM deleted WHERE id = 1)
  BEGIN
    RAISERROR('محمي: لا يمكن حذف هذا السجل (id = 1).', 16, 1);
    RETURN;
  END

  -- خلاف ذلك، احذف السجلات المطلوبة
  DELETE e
  FROM dbo.employees e
  JOIN deleted d ON e.id = d.id;
END;
GO

--DROP TRIGGER trg_PreventDelete_Employees_ById;
