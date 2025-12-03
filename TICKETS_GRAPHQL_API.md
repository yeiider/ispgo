# Tickets GraphQL API Documentation

## Tabla de Contenidos
- [Introducción](#introducción)
- [Queries](#queries)
- [Mutations](#mutations)
- [Tipos](#tipos)
- [Ejemplos de Uso](#ejemplos-de-uso)

---

## Introducción

Esta API GraphQL proporciona funcionalidad completa para gestionar tickets de soporte, incluyendo:
- Creación y gestión de tickets
- Asignación de múltiples usuarios
- Asignación opcional de customers y servicios
- Comentarios internos y externos
- Archivos adjuntos
- Sistema de etiquetas (labels)

**Nota importante:** Los campos `customer_id` y `service_id` son **opcionales** en los tickets, permitiendo crear tickets generales que no estén vinculados a clientes o servicios específicos.

---

## Queries

### 1. Listar Tickets con Filtros

```graphql
query GetTickets($status: String, $priority: String, $customerId: ID, $serviceId: ID, $title: String) {
  tickets(
    status: $status
    priority: $priority
    customer_id: $customerId
    service_id: $serviceId
    title: $title
    first: 20
    page: 1
  ) {
    paginatorInfo {
      count
      currentPage
      total
      lastPage
    }
    data {
      id
      title
      description
      status
      priority
      issue_type
      contact_method
      closed_at
      created_at
      updated_at
      customer {
        id
        first_name
        last_name
        email_address
      }
      service {
        id
        service_ip
        service_status
      }
      users {
        id
        name
        email
      }
      labels {
        name
        color
      }
      comments {
        id
        comment
        is_internal
        created_at
        user {
          id
          name
        }
      }
      attachments {
        id
        file_name
        file_path
        file_type
        file_size
        created_at
        user {
          id
          name
        }
      }
    }
  }
}
```

**Variables:**
```json
{
  "status": "open",
  "priority": "high",
  "customerId": "123",
  "serviceId": "456",
  "title": "%problema%"
}
```

**Valores permitidos:**
- **status**: `open`, `in_progress`, `resolved`, `closed`
- **priority**: `low`, `medium`, `high`, `urgent`

---

### 2. Obtener un Ticket Específico

```graphql
query GetTicket($id: ID!) {
  ticket(id: $id) {
    id
    title
    description
    status
    priority
    issue_type
    contact_method
    resolution_notes
    closed_at
    created_at
    updated_at
    customer {
      id
      first_name
      last_name
      email_address
      phone_number
    }
    service {
      id
      service_ip
      service_status
      plan {
        name
      }
    }
    users {
      id
      name
      email
      role
    }
    labels {
      name
      color
    }
    comments {
      id
      comment
      is_internal
      created_at
      user {
        id
        name
        email
      }
    }
    attachments {
      id
      file_name
      file_path
      file_type
      file_size
      created_at
      user {
        id
        name
      }
    }
  }
}
```

**Variables:**
```json
{
  "id": "123"
}
```

---

### 3. Obtener Mis Tickets Asignados

```graphql
query GetMyTickets {
  myTickets {
    id
    title
    description
    status
    priority
    issue_type
    created_at
    customer {
      id
      first_name
      last_name
    }
    service {
      id
      service_ip
    }
    labels {
      name
      color
    }
  }
}
```

---

## Mutations

### 1. Crear Ticket

```graphql
mutation CreateTicket($input: CreateTicketInput!) {
  createTicket(
    customer_id: $input.customer_id
    service_id: $input.service_id
    issue_type: $input.issue_type
    priority: $input.priority
    status: $input.status
    title: $input.title
    description: $input.description
    contact_method: $input.contact_method
    user_ids: $input.user_ids
  ) {
    id
    title
    description
    status
    priority
    issue_type
    created_at
    customer {
      id
      first_name
      last_name
    }
    service {
      id
      service_ip
    }
    users {
      id
      name
      email
    }
  }
}
```

**Variables:**
```json
{
  "input": {
    "customer_id": "123",
    "service_id": "456",
    "issue_type": "Problema de Conexión",
    "priority": "high",
    "status": "open",
    "title": "Sin servicio de internet",
    "description": "El cliente reporta que no tiene conexión a internet desde hace 2 horas",
    "contact_method": "Teléfono",
    "user_ids": ["10", "15", "20"]
  }
}
```

**Ejemplo sin customer ni service (ticket general):**
```json
{
  "input": {
    "issue_type": "Consulta General",
    "priority": "low",
    "title": "Pregunta sobre facturación",
    "description": "Cliente pregunta sobre cargos en factura",
    "contact_method": "Email",
    "user_ids": ["10"]
  }
}
```

**Campos requeridos:**
- `issue_type`: String!
- `priority`: String! (`low`, `medium`, `high`, `urgent`)
- `title`: String!
- `description`: String!

**Campos opcionales:**
- `customer_id`: ID (puede ser null)
- `service_id`: ID (puede ser null)
- `status`: String (default: `open`)
- `contact_method`: String
- `user_ids`: [ID!] (array de IDs de usuarios a asignar)

---

### 2. Actualizar Ticket

```graphql
mutation UpdateTicket($id: ID!, $input: UpdateTicketInput!) {
  updateTicket(
    id: $id
    customer_id: $input.customer_id
    service_id: $input.service_id
    issue_type: $input.issue_type
    priority: $input.priority
    status: $input.status
    title: $input.title
    description: $input.description
    contact_method: $input.contact_method
    resolution_notes: $input.resolution_notes
  ) {
    id
    title
    description
    status
    priority
    resolution_notes
    updated_at
  }
}
```

**Variables:**
```json
{
  "id": "123",
  "input": {
    "status": "in_progress",
    "priority": "urgent",
    "resolution_notes": "Técnico asignado, en camino al domicilio"
  }
}
```

---

### 3. Eliminar Ticket

```graphql
mutation DeleteTicket($id: ID!) {
  deleteTicket(id: $id) {
    success
    message
  }
}
```

**Variables:**
```json
{
  "id": "123"
}
```

---

### 4. Asignar Usuarios a Ticket

```graphql
mutation AssignUsersToTicket($ticketId: ID!, $userIds: [ID!]!) {
  assignUsersToTicket(ticket_id: $ticketId, user_ids: $userIds) {
    id
    title
    users {
      id
      name
      email
      role
    }
  }
}
```

**Variables:**
```json
{
  "ticketId": "123",
  "userIds": ["10", "15", "20"]
}
```

**Nota:** Esta mutation agrega usuarios sin remover los existentes.

---

### 5. Remover Usuario de Ticket

```graphql
mutation RemoveUserFromTicket($ticketId: ID!, $userId: ID!) {
  removeUserFromTicket(ticket_id: $ticketId, user_id: $userId) {
    id
    users {
      id
      name
    }
  }
}
```

**Variables:**
```json
{
  "ticketId": "123",
  "userId": "15"
}
```

---

### 6. Asignar Customer a Ticket

```graphql
mutation AssignCustomerToTicket($ticketId: ID!, $customerId: ID) {
  assignCustomerToTicket(ticket_id: $ticketId, customer_id: $customerId) {
    id
    customer {
      id
      first_name
      last_name
      email_address
    }
  }
}
```

**Variables para asignar:**
```json
{
  "ticketId": "123",
  "customerId": "456"
}
```

**Variables para quitar customer (establecer null):**
```json
{
  "ticketId": "123",
  "customerId": null
}
```

---

### 7. Asignar Servicio a Ticket

```graphql
mutation AssignServiceToTicket($ticketId: ID!, $serviceId: ID) {
  assignServiceToTicket(ticket_id: $ticketId, service_id: $serviceId) {
    id
    service {
      id
      service_ip
      service_status
    }
  }
}
```

**Variables para asignar:**
```json
{
  "ticketId": "123",
  "serviceId": "789"
}
```

**Variables para quitar servicio (establecer null):**
```json
{
  "ticketId": "123",
  "serviceId": null
}
```

---

## Comentarios

### 8. Agregar Comentario

```graphql
mutation AddTicketComment($ticketId: ID!, $comment: String!, $isInternal: Boolean) {
  addTicketComment(
    ticket_id: $ticketId
    comment: $comment
    is_internal: $isInternal
  ) {
    id
    comment
    is_internal
    created_at
    user {
      id
      name
      email
    }
  }
}
```

**Variables para comentario público:**
```json
{
  "ticketId": "123",
  "comment": "Se realizó visita técnica, router reemplazado",
  "isInternal": false
}
```

**Variables para comentario interno:**
```json
{
  "ticketId": "123",
  "comment": "Cliente tiene deuda pendiente, verificar antes de cerrar",
  "isInternal": true
}
```

---

### 9. Actualizar Comentario

```graphql
mutation UpdateTicketComment($id: ID!, $comment: String!) {
  updateTicketComment(id: $id, comment: $comment) {
    id
    comment
    updated_at
  }
}
```

**Variables:**
```json
{
  "id": "789",
  "comment": "Se realizó visita técnica, router reemplazado. Cliente satisfecho."
}
```

**Nota:** Solo el autor del comentario puede actualizarlo.

---

### 10. Eliminar Comentario

```graphql
mutation DeleteTicketComment($id: ID!) {
  deleteTicketComment(id: $id) {
    success
    message
  }
}
```

**Variables:**
```json
{
  "id": "789"
}
```

**Nota:** Solo el autor del comentario puede eliminarlo.

---

## Archivos Adjuntos

### 11. Agregar Archivo Adjunto

```graphql
mutation AddTicketAttachment($input: AddAttachmentInput!) {
  addTicketAttachment(
    ticket_id: $input.ticket_id
    file_name: $input.file_name
    file_path: $input.file_path
    file_type: $input.file_type
    file_size: $input.file_size
  ) {
    id
    file_name
    file_path
    file_type
    file_size
    created_at
    user {
      id
      name
    }
  }
}
```

**Variables:**
```json
{
  "input": {
    "ticket_id": "123",
    "file_name": "captura_error.png",
    "file_path": "tickets/123/captura_error.png",
    "file_type": "image/png",
    "file_size": 245678
  }
}
```

**Nota:** El frontend debe primero subir el archivo al servidor y luego registrarlo en el ticket con esta mutation.

---

### 12. Eliminar Archivo Adjunto

```graphql
mutation DeleteTicketAttachment($id: ID!) {
  deleteTicketAttachment(id: $id) {
    success
    message
  }
}
```

**Variables:**
```json
{
  "id": "456"
}
```

**Nota:** Solo el usuario que subió el archivo puede eliminarlo.

---

## Etiquetas (Labels)

### 13. Agregar Etiqueta

```graphql
mutation AddTicketLabel($ticketId: ID!, $name: String!, $color: String) {
  addTicketLabel(ticket_id: $ticketId, name: $name, color: $color) {
    id
    labels {
      name
      color
    }
  }
}
```

**Variables:**
```json
{
  "ticketId": "123",
  "name": "Urgente",
  "color": "#FF0000"
}
```

**Colores sugeridos:**
- Urgente: `#FF0000` (Rojo)
- Importante: `#FFA500` (Naranja)
- En proceso: `#3498db` (Azul)
- Resuelto: `#2ECC71` (Verde)
- Pendiente: `#F39C12` (Amarillo)

---

### 14. Remover Etiqueta

```graphql
mutation RemoveTicketLabel($ticketId: ID!, $name: String!) {
  removeTicketLabel(ticket_id: $ticketId, name: $name) {
    id
    labels {
      name
      color
    }
  }
}
```

**Variables:**
```json
{
  "ticketId": "123",
  "name": "Urgente"
}
```

---

## Tipos

### Ticket

```graphql
type Ticket {
  id: ID!
  customer_id: ID
  service_id: ID
  issue_type: String!
  priority: String!          # low, medium, high, urgent
  status: String!            # open, in_progress, resolved, closed
  title: String!
  description: String!
  resolution_notes: String
  contact_method: String
  closed_at: DateTime
  labels: [TicketLabel!]
  created_at: DateTime!
  updated_at: DateTime!

  # Relaciones
  customer: Customer
  service: Service
  users: [User!]!
  comments: [TicketComment!]!
  attachments: [TicketAttachment!]!
}
```

---

### TicketLabel

```graphql
type TicketLabel {
  name: String!
  color: String!
}
```

---

### TicketComment

```graphql
type TicketComment {
  id: ID!
  ticket_id: ID!
  user_id: ID!
  comment: String!
  is_internal: Boolean!
  created_at: DateTime!
  updated_at: DateTime!

  # Relaciones
  ticket: Ticket!
  user: User!
}
```

---

### TicketAttachment

```graphql
type TicketAttachment {
  id: ID!
  ticket_id: ID!
  user_id: ID!
  file_name: String!
  file_path: String!
  file_type: String
  file_size: Int
  created_at: DateTime!
  updated_at: DateTime!

  # Relaciones
  ticket: Ticket!
  user: User!
}
```

---

## Ejemplos de Uso

### Caso 1: Crear ticket de cliente con problema de conexión

```graphql
mutation {
  createTicket(
    customer_id: "123"
    service_id: "456"
    issue_type: "Problema de Conexión"
    priority: "high"
    title: "Cliente sin internet"
    description: "Cliente reporta intermitencia en el servicio desde esta mañana"
    contact_method: "Teléfono"
    user_ids: ["10", "15"]
  ) {
    id
    title
    status
    customer {
      first_name
      last_name
    }
    users {
      name
    }
  }
}
```

---

### Caso 2: Agregar comentario y cambiar estado

```graphql
# Paso 1: Agregar comentario
mutation {
  addTicketComment(
    ticket_id: "123"
    comment: "Técnico asignado, visita programada para mañana 10:00 AM"
    is_internal: false
  ) {
    id
    comment
  }
}

# Paso 2: Actualizar estado
mutation {
  updateTicket(
    id: "123"
    status: "in_progress"
  ) {
    id
    status
  }
}
```

---

### Caso 3: Cerrar ticket con resolución

```graphql
mutation {
  updateTicket(
    id: "123"
    status: "closed"
    resolution_notes: "Problema resuelto. Se reemplazó cable de fibra óptica dañado. Cliente confirma servicio funcionando correctamente."
  ) {
    id
    status
    resolution_notes
    closed_at
  }
}
```

---

### Caso 4: Crear ticket general sin cliente ni servicio

```graphql
mutation {
  createTicket(
    issue_type: "Consulta General"
    priority: "low"
    title: "Información sobre nuevos planes"
    description: "Cliente pregunta por planes disponibles en su zona"
    contact_method: "WhatsApp"
    user_ids: ["5"]
  ) {
    id
    title
    status
  }
}
```

---

### Caso 5: Buscar tickets por filtros

```graphql
query {
  tickets(
    status: "open"
    priority: "high"
    first: 10
  ) {
    data {
      id
      title
      priority
      created_at
      customer {
        first_name
        last_name
      }
      users {
        name
      }
    }
  }
}
```

---

### Caso 6: Workflow completo de ticket

```graphql
# 1. Crear ticket
mutation CreateNewTicket {
  createTicket(
    customer_id: "123"
    service_id: "456"
    issue_type: "Falla Técnica"
    priority: "urgent"
    title: "Router sin señal"
    description: "Router completamente apagado, no enciende ninguna luz"
    contact_method: "Teléfono"
    user_ids: ["10"]
  ) {
    id
  }
}

# 2. Agregar etiqueta
mutation AddUrgentLabel {
  addTicketLabel(
    ticket_id: "123"
    name: "Crítico"
    color: "#FF0000"
  ) {
    id
    labels {
      name
      color
    }
  }
}

# 3. Asignar más técnicos
mutation AssignMoreUsers {
  assignUsersToTicket(
    ticket_id: "123"
    user_ids: ["15", "20"]
  ) {
    id
    users {
      name
    }
  }
}

# 4. Agregar comentario interno
mutation InternalComment {
  addTicketComment(
    ticket_id: "123"
    comment: "Cliente VIP, atender con prioridad máxima"
    is_internal: true
  ) {
    id
  }
}

# 5. Agregar comentario público
mutation PublicComment {
  addTicketComment(
    ticket_id: "123"
    comment: "Técnico en camino, llegará en 30 minutos"
    is_internal: false
  ) {
    id
  }
}

# 6. Actualizar a en progreso
mutation UpdateToInProgress {
  updateTicket(
    id: "123"
    status: "in_progress"
  ) {
    id
    status
  }
}

# 7. Subir foto de evidencia
mutation AddEvidence {
  addTicketAttachment(
    ticket_id: "123"
    file_name: "router_danado.jpg"
    file_path: "tickets/123/router_danado.jpg"
    file_type: "image/jpeg"
    file_size: 345678
  ) {
    id
    file_name
  }
}

# 8. Resolver y cerrar
mutation ResolveTicket {
  updateTicket(
    id: "123"
    status: "closed"
    resolution_notes: "Router reemplazado por uno nuevo. Servicio funcionando correctamente. Cliente satisfecho."
  ) {
    id
    status
    resolution_notes
    closed_at
  }
}
```

---

## Notas Importantes para el Frontend

### 1. **Autenticación**
Todas las mutaciones requieren autenticación. Asegúrate de enviar el token en el header:
```javascript
headers: {
  'Authorization': 'Bearer YOUR_TOKEN_HERE'
}
```

### 2. **Paginación**
Las queries que retornan listas usan paginación de Lighthouse:
```graphql
tickets(first: 20, page: 1) {
  paginatorInfo {
    count
    currentPage
    total
    lastPage
    hasMorePages
  }
  data {
    # ... campos
  }
}
```

### 3. **Manejo de Archivos**
Para subir archivos:
1. Usar endpoint REST para subir archivo (ej: `POST /api/upload`)
2. Obtener la ruta del archivo
3. Usar `addTicketAttachment` mutation para registrarlo

### 4. **Estados del Ticket**
- `open` - Recién creado, esperando asignación
- `in_progress` - Técnico trabajando en él
- `resolved` - Problema solucionado, esperando confirmación
- `closed` - Ticket cerrado (se establece `closed_at` automáticamente)

### 5. **Prioridades**
- `low` - Baja prioridad
- `medium` - Prioridad media
- `high` - Alta prioridad
- `urgent` - Urgente (requiere atención inmediata)

### 6. **Comentarios Internos vs Públicos**
- **Públicos** (`is_internal: false`): Visibles para el cliente
- **Internos** (`is_internal: true`): Solo visibles para el equipo

### 7. **Permisos**
- Solo el autor puede editar/eliminar sus comentarios
- Solo el autor puede eliminar sus archivos adjuntos
- Los administradores pueden gestionar todos los tickets

---

## Endpoint GraphQL

```
POST https://tu-dominio.com/graphql
Content-Type: application/json
Authorization: Bearer {token}
```

---

## Soporte

Para más información o reportar problemas, contactar al equipo de desarrollo.
