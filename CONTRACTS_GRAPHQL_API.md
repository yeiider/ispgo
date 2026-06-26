# Contracts & Templates GraphQL API Documentation

## Tabla de Contenidos
- [Introducción](#introducción)
- [Estados de los Contratos](#estados-de-los-contratos)
- [Queries](#queries)
  - [Contratos](#contratos-queries)
  - [Plantillas HTML/Contratos](#plantillas-html-queries)
  - [Plantillas de Correo](#plantillas-de-correo-queries)
- [Mutations](#mutations)
  - [Contratos](#contratos-mutations)
  - [Plantillas HTML/Contratos](#plantillas-html-mutations)
  - [Plantillas de Correo](#plantillas-de-correo-mutations)

---

## Introducción

Esta API GraphQL proporciona la funcionalidad completa para gestionar el ciclo de vida de los **Contratos** y la integración de **Plantillas de Contratos (HtmlTemplates)** y **Plantillas de Correo (EmailTemplates)** en la plataforma.

Características principales:
- Gestión y control de contratos asociados a clientes (`customer`) y servicios (`service`).
- Flujo de estados del contrato desde borrador hasta la firma, revisión, aprobación o rechazo.
- Integración segura con Amazon S3 para guardar documentos de soporte (Cédula obligatoria, Recibo de gas/agua opcional) y el PDF firmado final.
- Notificaciones automáticas de correo electrónico basadas en plantillas dinámicas administrables.

---

## Estados de los Contratos

El campo `status` del contrato puede tener los siguientes valores:
1. **`draft` (Borrador)**: Contrato registrado en la base de datos pero aún no notificado al cliente.
2. **`sent` (Pendiente de Firma)**: Enlace enviado por correo electrónico para que el cliente inicie la firma y subida de documentos.
3. **`signed` (Pendiente de Revisión)**: El cliente ha firmado y cargado la documentación correspondiente. Está a la espera de la confirmación del administrador.
4. **`approved` (Aprobado/Activo)**: El administrador ha revisado y aprobado el contrato. El servicio se activa o permanece en vigencia.
5. **`rejected` (Rechazado)**: Documentación inválida o firma incorrecta. El cliente es notificado con el motivo y debe volver a firmar.

---

## Queries

<a name="contratos-queries"></a>
### 1. Listar Contratos (Paginado)
Obtiene la lista de contratos registrados en el sistema, permitiendo filtrar por cliente, estado y buscar texto relacionado con el nombre o documento del cliente.

```graphql
query GetContracts($status: String, $customerId: ID, $search: String, $sortColumn: String, $sortDirection: String, $first: Int!, $page: Int!) {
  contracts(
    status: $status
    customer_id: $customerId
    search: $search
    sort_column: $sortColumn
    sort_direction: $sortDirection
    first: $first
    page: $page
  ) {
    paginatorInfo {
      count
      currentPage
      total
      lastPage
    }
    data {
      id
      status
      start_date
      end_date
      is_signed
      signed_at
      contract_pdf_path
      contract_pdf_url # Genera URL firmada temporal de S3
      cedula_path
      cedula_url       # Genera URL firmada temporal de S3
      utility_bill_path
      utility_bill_url # Genera URL firmada temporal de S3
      customer {
        id
        first_name
        last_name
        identity_document
      }
      service {
        id
        service_ip
        service_status
      }
    }
  }
}
```

### 2. Obtener un Contrato por ID
Obtiene los detalles específicos de un único contrato mediante su UUID.

```graphql
query GetContract($id: ID!) {
  contract(id: $id) {
    id
    status
    start_date
    end_date
    is_signed
    signed_at
    contract_pdf_url
    cedula_url
    utility_bill_url
    customer {
      id
      first_name
      last_name
    }
    service {
      id
      service_ip
    }
  }
}
```

<a name="plantillas-html-queries"></a>
### 3. Listar Plantillas HTML de Contratos
Obtiene el listado de plantillas HTML usadas para renderizar la base del documento del contrato.

```graphql
query GetHtmlTemplates($search: String, $first: Int!, $page: Int!) {
  htmlTemplates(search: $search, first: $first, page: $page) {
    paginatorInfo {
      total
    }
    data {
      id
      name
      body
      styles
      entity
    }
  }
}
```

### 4. Obtener Plantilla HTML por ID
```graphql
query GetHtmlTemplate($id: ID!) {
  htmlTemplate(id: $id) {
    id
    name
    body
    styles
  }
}
```

<a name="plantillas-de-correo-queries"></a>
### 5. Listar Plantillas de Correo
Obtiene las plantillas de correo configuradas para notificar a los usuarios en las distintas transiciones de estado.

```graphql
query GetEmailTemplates($search: String, $first: Int!, $page: Int!) {
  emailTemplates(search: $search, first: $first, page: $page) {
    paginatorInfo {
      total
    }
    data {
      id
      name
      subject
      body
      is_active
      entity
    }
  }
}
```

---

## Mutations

<a name="contratos-mutations"></a>
### 1. Crear Contrato
Crea un contrato en estado `draft` (borrador) vinculando un cliente y un servicio.

```graphql
mutation CreateContract($customer_id: ID!, $service_id: ID!, $start_date: Date!, $end_date: Date!) {
  createContract(
    customer_id: $customer_id
    service_id: $service_id
    start_date: $start_date
    end_date: $end_date
  ) {
    id
    status
    is_signed
  }
}
```

### 2. Actualizar Contrato
Modifica parámetros de vigencia o estado de un contrato existente.

```graphql
mutation UpdateContract($id: ID!, $start_date: Date, $end_date: Date, $status: String) {
  updateContract(
    id: $id
    start_date: $start_date
    end_date: $end_date
    status: $status
  ) {
    id
    status
    start_date
    end_date
  }
}
```

### 3. Eliminar Contrato
Elimina físicamente el contrato de la base de datos.

```graphql
mutation DeleteContract($id: ID!) {
  deleteContract(id: $id) {
    id
  }
}
```

### 4. Enviar Contrato al Cliente
Envía el enlace de firma por correo electrónico al cliente usando la plantilla configurada en `email_template_send`. Cambia el estado del contrato a `sent`.

```graphql
mutation SendContract($id: ID!) {
  sendContract(id: $id) {
    success
    message
    contract {
      id
      status
    }
  }
}
```

### 5. Reenviar Contrato al Cliente
Acción administrativa para reenviar el enlace al correo del cliente en caso de que lo solicite de nuevo.

```graphql
mutation ResendContract($id: ID!) {
  resendContract(id: $id) {
    success
    message
    contract {
      id
      status
    }
  }
}
```

### 6. Registrar Firma y Cargar Soportes (Lado Cliente)
Invocado cuando el cliente firma el contrato. Requiere la firma en formato base64 y las rutas temporales en S3 (`FileUploadMutation@uploadTempBase64`) para la Cédula (obligatorio) y el Recibo (opcional).
Mueve los archivos al directorio definitivo en S3, compila el contrato en PDF junto con la firma del representante y del cliente, y cambia el estado a `signed`.

```graphql
mutation SignContract($id: ID!, $signature_base64: String!, $cedula_temp_path: String!, $utility_bill_temp_path: String) {
  signContract(
    id: $id
    signature_base64: $signature_base64
    cedula_temp_path: $cedula_temp_path
    utility_bill_temp_path: $utility_bill_temp_path
  ) {
    success
    message
    contract {
      id
      status
      is_signed
      signed_at
      contract_pdf_url
      cedula_url
      utility_bill_url
    }
  }
}
```

### 7. Aprobar Contrato
Acción para que el administrador valide y acepte el contrato firmado. Envía el correo final utilizando la plantilla `email_template_approved` y adjunta el PDF firmado. Cambia el estado a `approved`.

```graphql
mutation ApproveContract($id: ID!) {
  approveContract(id: $id) {
    id
    status
    is_signed
  }
}
```

### 8. Rechazar Contrato
Acción para que el administrador rechace los documentos (por ejemplo, si el documento de identidad es borroso). Envía el motivo del rechazo al cliente usando la plantilla `email_template_rejected` permitiendo que el cliente reingrese al enlace. Cambia el estado a `rejected`.

```graphql
mutation RejectContract($id: ID!, $reason: String!) {
  rejectContract(id: $id, reason: $reason) {
    id
    status
  }
}
```

---

<a name="plantillas-html-mutations"></a>
### 9. CRUD Plantillas HTML (Contratos)

```graphql
# Crear Plantilla HTML
mutation CreateHtmlTemplate($name: String!, $body: String!, $styles: String, $entity: String) {
  createHtmlTemplate(name: $name, body: $body, styles: $styles, entity: $entity) {
    id
    name
  }
}

# Actualizar Plantilla HTML
mutation UpdateHtmlTemplate($id: ID!, $name: String, $body: String, $styles: String, $entity: String) {
  updateHtmlTemplate(id: $id, name: $name, body: $body, styles: $styles, entity: $entity) {
    id
    name
  }
}

# Eliminar Plantilla HTML
mutation DeleteHtmlTemplate($id: ID!) {
  deleteHtmlTemplate(id: $id) {
    id
  }
}
```

---

<a name="plantillas-de-correo-mutations"></a>
### 10. CRUD Plantillas de Correo

```graphql
# Crear Plantilla de Correo
mutation CreateEmailTemplate(
  $name: String!
  $subject: String
  $body: String!
  $styles: String
  $entity: String
  $is_active: Boolean
  $description: String
) {
  createEmailTemplate(
    name: $name
    subject: $subject
    body: $body
    styles: $styles
    entity: $entity
    is_active: $is_active
    description: $description
  ) {
    id
    name
  }
}

# Actualizar Plantilla de Correo
mutation UpdateEmailTemplate(
  $id: ID!
  $name: String
  $subject: String
  $body: String
  $styles: String
  $entity: String
  $is_active: Boolean
  $description: String
) {
  updateEmailTemplate(
    id: $id
    name: $name
    subject: $subject
    body: $body
    styles: $styles
    entity: $entity
    is_active: $is_active
    description: $description
  ) {
    id
    name
  }
}

# Eliminar Plantilla de Correo
mutation DeleteEmailTemplate($id: ID!) {
  deleteEmailTemplate(id: $id) {
    id
  }
}
```
