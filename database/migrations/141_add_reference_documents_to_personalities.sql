-- Adicionar campo para documentos de referência nas personalidades
ALTER TABLE personalities ADD COLUMN reference_documents TEXT NULL AFTER prompt;

-- Comentário: Este campo armazenará um JSON com os documentos de referência da personalidade
-- Formato: [{"name": "Nome do arquivo", "path": "/caminho/para/arquivo.pdf", "type": "pdf"}]