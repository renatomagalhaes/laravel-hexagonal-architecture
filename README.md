# 🏪 Laravel Hexagonal Architecture - Loja Virtual

Este projeto demonstra a implementação de uma **Arquitetura Hexagonal (Ports and Adapters)** em Laravel 12, seguindo princípios de **Clean Code** e **SOLID** para um sistema de loja virtual com produtos e categorias.

## 🎯 Objetivo

Este repositório serve como:
- **Aprendizado prático** da arquitetura hexagonal
- **Exemplo** demonstrando conhecimento em arquitetura limpa
- **Referência** para implementação de princípios SOLID e Clean Code

## 🏗️ Arquitetura

### Princípios Aplicados

#### 🔵 **SOLID**
- **S**RP: Cada classe tem uma única responsabilidade
- **O**CP: Extensível através de interfaces, fechado para modificação
- **L**SP: Subclasses podem substituir suas classes base
- **I**SP: Interfaces específicas em vez de uma interface geral
- **D**IP: Dependência de abstrações, não de implementações concretas

#### 🔶 **Clean Code**
- Nomes expressivos e intencionais
- Funções pequenas com responsabilidade única
- Comentários explicativos sobre a arquitetura
- Tratamento adequado de erros
- Testes como documentação viva

#### ⬡ **Hexagonal Architecture (Ports & Adapters)**
- **Domain**: Regras de negócio puras, sem dependências externas
- **Ports**: Interfaces que definem contratos
- **Adapters**: Implementações concretas dos ports
- **Application**: Orquestra os casos de uso
- **Infrastructure**: Detalhes técnicos (DB, HTTP, etc.)

## 📁 Estrutura do Projeto

Este projeto utiliza uma estrutura híbrida que combina a **Arquitetura Hexagonal** com as **convenções padrão do Laravel**, organizando a arquitetura de domínio em um diretório `Core` centralizado:

```
app/
├── Core/                    # 🏗️ Arquitetura Hexagonal Centralizada
│   ├── Domain/             # Regras de negócio puras
│   │   ├── Entities/       # Entidades do domínio
│   │   ├── ValueObjects/   # Objetos de valor
│   │   ├── Services/       # Serviços de domínio
│   │   └── Validators/     # Validações de regras de negócio
│   ├── Application/        # Casos de uso
│   │   ├── UseCases/       # Implementação dos casos de uso
│   │   └── DTOs/          # Data Transfer Objects
│   ├── Infrastructure/     # Detalhes técnicos
│   │   ├── Database/       # Implementações de repositórios
│   │   ├── Http/          # Controllers da arquitetura
│   │   └── External/      # Integrações externas
│   └── Ports/             # Interfaces (contratos)
│       ├── Repositories/   # Contratos de repositórios
│       └── Services/       # Contratos de serviços
├── Http/                   # 🌐 Controllers Padrão Laravel
│   ├── Controllers/        # Controllers HTTP padrão
│   └── Requests/          # Validações de entrada (Laravel)
├── Models/                 # 📊 Models Eloquent (se necessário)
└── Providers/             # ⚙️ Service Providers
```

### 🎯 **Estratégia de Organização**

#### **Diretório `Core/`**
- **Vantagem**: Centraliza toda a arquitetura hexagonal
- **Benefício**: Facilita migração para outros frameworks
- **Organização**: Separação clara entre domínio e framework

#### **Controllers Híbridos**
- **`app/Http/Controllers/`**: Controllers padrão Laravel para HTTP
- **`app/Core/Infrastructure/Http/`**: Controllers específicos da arquitetura
- **Estratégia**: Injeção de dependência dos Use Cases nos controllers padrão

#### **Validações em Camadas**
- **Camada 1**: `app/Http/Requests/` - Validações de entrada (Laravel)
- **Camada 2**: `app/Core/Domain/Validators/` - Regras de negócio
- **Camada 3**: Validações nos Use Cases - Orquestração

## 🐳 **Estrutura Docker**

Este projeto utiliza uma **estrutura Docker organizada** com suporte a múltiplos ambientes:

```
docker/
├── Dockerfile.dev          # 🛠️ Desenvolvimento (otimizado para dev)
├── Dockerfile.prod         # 🚀 Produção (multi-stage build otimizado)
├── scripts/
│   ├── install.sh          # Script de instalação completo
│   ├── build-dev.sh        # Build para desenvolvimento
│   └── build-prod.sh       # Build para produção
├── nginx/
│   └── default.conf        # Configuração do Nginx
├── php/
│   └── local.ini           # Configuração do PHP
└── mysql/
    └── my.cnf              # Configuração do MySQL
```

### **🎯 Vantagens da Organização Docker**

#### **Desenvolvimento (`Dockerfile.dev`)**
- ✅ **Hot reload**: Volumes montados para desenvolvimento
- ✅ **Debug tools**: Xdebug e ferramentas de desenvolvimento
- ✅ **Dependências completas**: Todas as extensões necessárias

#### **Produção (`Dockerfile.prod`)**
- ✅ **Multi-stage build**: Imagem otimizada e menor
- ✅ **Alpine Linux**: Base minimalista para produção
- ✅ **Assets otimizados**: Build dos assets incluído
- ✅ **Segurança**: Usuário não-root e permissões adequadas

## 🐛 **Configuração Xdebug**

Este projeto inclui **Xdebug sempre ativo** no ambiente de desenvolvimento, oferecendo recursos de **debug** e **coverage**:

### **🎯 Recursos Disponíveis**

#### **Debug**
- ✅ **Step debugging**: Debug passo a passo no código
- ✅ **Breakpoints**: Pontos de parada para análise
- ✅ **Variable inspection**: Inspeção de variáveis em tempo real
- ✅ **Stack traces**: Rastreamento de chamadas de função

#### **Coverage**
- ✅ **Code coverage**: Análise de cobertura de código
- ✅ **Test coverage**: Relatórios de cobertura de testes
- ✅ **HTML reports**: Relatórios visuais em HTML

### **🔧 Configuração da IDE**

#### **PHPStorm/IntelliJ**
1. **File → Settings → PHP → Debug**
2. **Xdebug port**: `9003`
3. **Can accept external connections**: ✅
4. **Path mappings**: `/var/www/html` → `./`

#### **VS Code**
1. **Instalar extensão**: PHP Debug
2. **Configurar launch.json**:
```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/var/www/html": "${workspaceFolder}"
            }
        }
    ]
}
```

### **🚀 Como Usar**

#### **Debug**
- **Xdebug está sempre ativo** no ambiente de desenvolvimento
- **Configure sua IDE** para conectar na porta 9003
- **Breakpoints funcionam automaticamente**

#### **Testes com Coverage**
```bash
make test-coverage # Executa testes com coverage
```

#### **Verificar Status**
```bash
make xdebug-status # Mostra configurações atuais
```

#### **Personalizar Configuração**
- **Edite o arquivo**: `docker/php/xdebug.ini`
- **Reconstrua o container**: `make build-dev`
- **Reinicie**: `make restart`

### **⚡ Configuração Simples**

- ✅ **Sempre ativo**: Debug e coverage disponíveis
- ✅ **Configurável**: Edite `docker/php/xdebug.ini`
- ✅ **IDE ready**: Configuração pronta para VS Code e PHPStorm
- ✅ **Performance**: Configurações otimizadas para desenvolvimento

## 🚀 Configuração do Ambiente

### Pré-requisitos
- Docker
- Docker Compose
- Make

### Instalação

1. **Clone o repositório**
```bash
git clone git@github.com:renatomagalhaes/laravel-hexagonal-architecture.git
cd laravel-hexagonal-architecture
```

2. **Configure o ambiente**
```bash
make install
```

3. **Inicie o ambiente de desenvolvimento**
```bash
make up
```

4. **Execute os testes**
```bash
make test
```

## 🛠️ Comandos Disponíveis

### **Comandos Básicos**
| Comando | Descrição |
|---------|-----------|
| `make install` | Instala dependências e configura o ambiente |
| `make up` | Inicia os containers Docker |
| `make down` | Para os containers Docker |
| `make test` | Executa os testes |
| `make artisan` | Executa comandos Artisan dentro do container |
| `make composer` | Executa comandos Composer dentro do container |
| `make shell` | Acessa o shell do container da aplicação |

### **Comandos de Build**
| Comando | Descrição |
|---------|-----------|
| `make build-dev` | Constrói imagem de desenvolvimento |
| `make build-prod` | Constrói imagem de produção otimizada |
| `make build-no-cache` | Constrói sem cache (desenvolvimento) |

### **Scripts Docker**
| Script | Descrição |
|---------|-----------|
| `docker/scripts/install.sh` | Script de instalação completo |
| `docker/scripts/build-dev.sh` | Build para desenvolvimento |
| `docker/scripts/build-prod.sh` | Build para produção |

### **Comandos Xdebug**
| Comando | Descrição |
|---------|-----------|
| `make xdebug-status` | Mostra status e configurações do Xdebug |
| `make test-coverage` | Executa testes com coverage |

## 🧪 Estratégia de Testes

Este projeto segue a abordagem **TDD (Test-Driven Development)**:

1. **Escrever o teste primeiro** - Define o comportamento esperado
2. **Implementar o mínimo** - Código que faz o teste passar
3. **Refatorar** - Melhorar o código mantendo os testes passando

### Tipos de Testes
- **Unit Tests**: Testam unidades isoladas (classes, métodos)
- **Integration Tests**: Testam integração entre componentes
- **Feature Tests**: Testam funcionalidades completas

## 📚 Conceitos Implementados

### Domain Layer
- **Entities**: Objetos com identidade e comportamento
- **Value Objects**: Objetos imutáveis sem identidade
- **Domain Services**: Lógica de negócio que não pertence a uma entidade

### Application Layer
- **Use Cases**: Orquestram o fluxo de dados entre camadas
- **DTOs**: Transferem dados entre camadas sem expor entidades

### Infrastructure Layer
- **Repository Implementations**: Acesso a dados
- **HTTP Controllers**: Interface web
- **External Services**: Integrações com APIs externas

## 🔍 Estratégia de Validações

Este projeto implementa **validações em múltiplas camadas** para garantir robustez e separação de responsabilidades:

### **Camada 1: Validação de Entrada (Laravel)**
```php
// app/Http/Requests/CreateProductRequest.php
class CreateProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
```

### **Camada 2: Validação de Domínio**
```php
// app/Core/Domain/Validators/ProductValidator.php
class ProductValidator
{
    public function validateBusinessRules(Product $product): void
    {
        if ($product->getPrice() < 0) {
            throw new InvalidProductPriceException('Price cannot be negative');
        }
        
        // Regras de negócio específicas
        $this->validateProductCategoryRules($product);
    }
}
```

### **Camada 3: Orquestração nos Use Cases**
```php
// app/Core/Application/UseCases/CreateProductUseCase.php
class CreateProductUseCase
{
    public function execute(CreateProductDTO $dto): Product
    {
        $product = new Product($dto->name, $dto->price, $dto->categoryId);
        
        // Validação de domínio
        $this->productValidator->validateBusinessRules($product);
        
        return $this->productRepository->save($product);
    }
}
```

### **Vantagens desta Abordagem**
- ✅ **Separação de responsabilidades**: Cada camada tem seu propósito
- ✅ **Reutilização**: Validações de domínio podem ser usadas em qualquer contexto
- ✅ **Testabilidade**: Cada camada pode ser testada independentemente
- ✅ **Manutenibilidade**: Mudanças em uma camada não afetam as outras

## 🎓 Aprendizado

Este projeto foi desenvolvido com foco no aprendizado prático:

- **Comentários explicativos** no código para facilitar entendimento
- **Implementação passo a passo** seguindo TDD
- **Exemplos práticos** de cada conceito arquitetural
- **Documentação detalhada** para entrevistas técnicas

## 📖 Recursos de Estudo

- [Hexagonal Architecture](https://alistair.cockburn.us/hexagonal-architecture/)
- [Clean Architecture](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
- [SOLID Principles](https://en.wikipedia.org/wiki/SOLID)
- [Test-Driven Development](https://en.wikipedia.org/wiki/Test-driven_development)

## 🤝 Contribuição

Este é um projeto educacional. Sinta-se à vontade para:
- Fazer fork e experimentar
- Sugerir melhorias
- Reportar issues
- Compartilhar conhecimento

## 📄 Licença

Este projeto é de uso educacional e está disponível sob a licença MIT.

---

**Desenvolvido com ❤️ para aprendizado e demonstração de arquitetura limpa (Renato Magalhães)**