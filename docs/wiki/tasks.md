# Xavante Workflow Engine API Documentation

This document describes the core tasks and API endpoints for the Xavante Workflow Engine, a dynamic workflow execution system that enables the creation, management, and execution of business process workflows.

## Overview

The Xavante Workflow Engine provides a RESTful API for managing workflow definitions and their execution instances. The system supports complex business logic through states, tasks, variables, and transitions that can be dynamically configured and executed.

## Authentication & Authorization

### Creating Auth Credentials

Creates authentication credentials for system access. This endpoint generates a pair of access tokens based on client credentials.

**Endpoint:** `POST /api/v1/auth/credentials`

**Security:** This endpoint should be protected from external usage as it provides system access.

**Request:**
```http
POST /api/v1/auth/credentials
Content-Type: application/json

{
  "client_id": "workflow-client-001",
  "secret": "super-secret-key-12345"
}
```

**Response:**
```http
HTTP/1.1 201 Created
Content-Type: application/json

{
  "X-ACCESS-TOKEN": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "X-ACCESS-CHECK": "a1b2c3d4e5f6g7h8i9j0",
  "expires_at": "2025-09-21T10:30:00Z",
  "client_id": "workflow-client-001"
}
```

**Notes:**
- Creates a database record for the authenticated user
- Tokens should be securely stored and transmitted
- Access credentials have configurable expiration times

### Authenticate with Credentials

Authenticates users and generates JWT tokens for subsequent API requests.

**Endpoint:** `POST /api/v1/auth/login`

**Request:**
```http
POST /api/v1/auth/login
Content-Type: application/json
X-ACCESS-TOKEN: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
X-ACCESS-CHECK: a1b2c3d4e5f6g7h8i9j0

{
  "client_id": "workflow-client-001"
}
```

**Response:**
```http
HTTP/1.1 200 OK
Content-Type: application/json

{
  "jwt_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJ3b3JrZmxvdy1jbGllbnQtMDAxIiwiaWF0IjoxNjMyNDQ4MjAwLCJleHAiOjE2MzI0NTE4MDB9...",
  "token_type": "Bearer",
  "expires_in": 3600,
  "user_id": "user_12345",
  "permissions": ["workflow.create", "workflow.read", "workflow.execute"]
}
```

## Workflow Management

### Core Workflow Concepts

**State**: A specific condition or status within the workflow that represents where the execution currently stands. States define the current context and determine which tasks can be executed next. Each state has a unique identifier and may contain metadata about the current execution context. States can be initial (starting point), intermediate (processing steps), final (completion), or error states (exception handling).

**Task**: Something that needs to be executed, like a function. There are some pre-conditions, like some variables should attend some specific criteria. Tasks encapsulate business logic and can perform operations such as data processing, external API calls, user interactions, or system integrations. Each task has input parameters, execution logic, output results, and may define success/failure conditions that influence the workflow progression.

**Variable**: A data container that holds values throughout the workflow execution lifecycle. Variables can store input data, intermediate results, configuration parameters, or output values. They have types (string, number, boolean, object, array), scopes (global to workflow, local to task, or session-based), and can be modified by tasks as the workflow progresses. Variables enable data flow between different states and tasks.

**Transition**: A directed connection between states that defines the possible paths of execution flow. Transitions are triggered by task completion, conditions evaluation, or external events. Each transition may have guard conditions (rules that must be satisfied), triggers (events that activate the transition), and actions (operations performed during the transition). Transitions enable the workflow to move from one state to another based on business rules and execution results.

### Create a Workflow

Creates a new workflow definition with states, tasks, variables, and transitions.

**Endpoint:** `POST /api/v1/workflows`

**Request:**
```http
POST /api/v1/workflows
Content-Type: application/json
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

{
  "name": "Order Processing Workflow",
  "description": "Handles customer order processing from validation to fulfillment",
  "version": "1.0.0",
  "metadata": {
    "department": "sales",
    "priority": "high",
    "tags": ["order", "payment", "inventory"]
  },
  "states": [
    {
      "id": "start",
      "name": "Order Received",
      "type": "initial",
      "metadata": {}
    },
    {
      "id": "validate_order",
      "name": "Validating Order",
      "type": "intermediate",
      "metadata": {}
    },
    {
      "id": "process_payment",
      "name": "Processing Payment",
      "type": "intermediate",
      "metadata": {}
    },
    {
      "id": "fulfilled",
      "name": "Order Fulfilled",
      "type": "final",
      "metadata": {}
    },
    {
      "id": "error",
      "name": "Error State",
      "type": "error",
      "metadata": {}
    }
  ],
  "tasks": [
    {
      "id": "validate_order_task",
      "name": "Validate Order Details",
      "type": "function",
      "function_name": "validateOrderDetails",
      "input_parameters": ["order_data", "customer_info"],
      "output_parameters": ["validation_result", "error_messages"],
      "preconditions": [
        {
          "variable": "order_data",
          "condition": "not_empty"
        }
      ]
    },
    {
      "id": "payment_task",
      "name": "Process Payment",
      "type": "api_call",
      "endpoint": "https://payment-gateway.example.com/charge",
      "method": "POST",
      "input_parameters": ["payment_info", "amount"],
      "output_parameters": ["transaction_id", "payment_status"]
    }
  ],
  "variables": [
    {
      "id": "order_data",
      "name": "Order Data",
      "type": "object",
      "scope": "global",
      "default_value": {},
      "required": true
    },
    {
      "id": "customer_info",
      "name": "Customer Information",
      "type": "object",
      "scope": "global",
      "default_value": {},
      "required": true
    },
    {
      "id": "payment_status",
      "name": "Payment Status",
      "type": "string",
      "scope": "global",
      "default_value": "pending",
      "required": false
    }
  ],
  "transitions": [
    {
      "id": "start_to_validate",
      "from_state": "start",
      "to_state": "validate_order",
      "trigger": "order_received",
      "conditions": [],
      "actions": ["initialize_variables"]
    },
    {
      "id": "validate_to_payment",
      "from_state": "validate_order",
      "to_state": "process_payment",
      "trigger": "validation_success",
      "conditions": [
        {
          "variable": "validation_result",
          "operator": "equals",
          "value": "valid"
        }
      ],
      "actions": ["prepare_payment"]
    },
    {
      "id": "payment_to_fulfilled",
      "from_state": "process_payment",
      "to_state": "fulfilled",
      "trigger": "payment_success",
      "conditions": [
        {
          "variable": "payment_status",
          "operator": "equals",
          "value": "completed"
        }
      ],
      "actions": ["send_confirmation"]
    },
    {
      "id": "any_to_error",
      "from_state": "*",
      "to_state": "error",
      "trigger": "error_occurred",
      "conditions": [],
      "actions": ["log_error", "notify_admin"]
    }
  ]
}
```

**Response:**
```http
HTTP/1.1 201 Created
Content-Type: application/json

{
  "id": "workflow_67890",
  "name": "Order Processing Workflow",
  "version": "1.0.0",
  "status": "active",
  "created_at": "2025-09-20T14:30:00Z",
  "updated_at": "2025-09-20T14:30:00Z",
  "owner_id": "user_12345",
  "metadata": {
    "department": "sales",
    "priority": "high",
    "tags": ["order", "payment", "inventory"]
  }
}
```

### Modify a Workflow

Updates an existing workflow by adding, updating, or removing elements such as states, tasks, variables, and transitions.

**Endpoint:** `PATCH /api/v1/workflows/{workflow_id}`

**Request:**
```http
PATCH /api/v1/workflows/workflow_67890
Content-Type: application/json
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

{
  "operations": [
    {
      "op": "add",
      "path": "/states/-",
      "value": {
        "id": "review_required",
        "name": "Manual Review Required",
        "type": "intermediate",
        "metadata": {
          "requires_human_intervention": true
        }
      }
    },
    {
      "op": "replace",
      "path": "/tasks/0/input_parameters",
      "value": ["order_data", "customer_info", "business_rules"]
    },
    {
      "op": "remove",
      "path": "/variables/2"
    }
  ]
}
```

**Response:**
```http
HTTP/1.1 200 OK
Content-Type: application/json

{
  "id": "workflow_67890",
  "name": "Order Processing Workflow",
  "version": "1.0.1",
  "status": "active",
  "updated_at": "2025-09-20T15:45:00Z",
  "changes_summary": {
    "states_added": 1,
    "tasks_modified": 1,
    "variables_removed": 1
  }
}
```

## Workflow Execution

### Create a Workflow Instance

Creates a new execution instance (job) of a workflow definition with initial data.

**Endpoint:** `POST /api/v1/workflows/{workflow_id}/instances`

**Request:**
```http
POST /api/v1/workflows/workflow_67890/instances
Content-Type: application/json
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

{
  "instance_name": "Order #12345 Processing",
  "initial_data": {
    "order_data": {
      "order_id": "ORD-12345",
      "items": [
        {"product_id": "PROD-001", "quantity": 2, "price": 29.99}
      ],
      "total_amount": 59.98
    },
    "customer_info": {
      "customer_id": "CUST-789",
      "email": "customer@example.com",
      "shipping_address": {
        "street": "123 Main St",
        "city": "Anytown",
        "state": "CA",
        "zip": "12345"
      }
    }
  },
  "metadata": {
    "priority": "normal",
    "source": "web_order",
    "correlation_id": "corr-12345-6789"
  }
}
```

**Response:**
```http
HTTP/1.1 201 Created
Content-Type: application/json

{
  "instance_id": "instance_abc123",
  "workflow_id": "workflow_67890",
  "instance_name": "Order #12345 Processing",
  "status": "running",
  "current_state": "start",
  "created_at": "2025-09-20T16:00:00Z",
  "variables": {
    "order_data": {
      "order_id": "ORD-12345",
      "items": [
        {"product_id": "PROD-001", "quantity": 2, "price": 29.99}
      ],
      "total_amount": 59.98
    },
    "customer_info": {
      "customer_id": "CUST-789",
      "email": "customer@example.com"
    },
    "payment_status": "pending"
  }
}
```

### Evaluate a Workflow Instance

Sends a signal to a workflow instance to trigger state transitions and task execution.

**Endpoint:** `POST /api/v1/workflows/{workflow_id}/instances/{instance_id}/evaluate`

**Request:**
```http
POST /api/v1/workflows/workflow_67890/instances/instance_abc123/evaluate
Content-Type: application/json
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

{
  "signal": "order_received",
  "data": {
    "timestamp": "2025-09-20T16:00:00Z",
    "source": "order_api"
  },
  "variables_update": {
    "validation_result": "valid",
    "customer_verified": true
  }
}
```

**Response:**
```http
HTTP/1.1 200 OK
Content-Type: application/json

{
  "instance_id": "instance_abc123",
  "previous_state": "start",
  "current_state": "validate_order",
  "transitions_executed": [
    {
      "transition_id": "start_to_validate",
      "executed_at": "2025-09-20T16:00:01Z",
      "actions_performed": ["initialize_variables"]
    }
  ],
  "tasks_executed": [
    {
      "task_id": "validate_order_task",
      "status": "completed",
      "started_at": "2025-09-20T16:00:01Z",
      "completed_at": "2025-09-20T16:00:03Z",
      "output": {
        "validation_result": "valid",
        "error_messages": []
      }
    }
  ],
  "updated_variables": {
    "validation_result": "valid",
    "customer_verified": true
  },
  "next_possible_signals": ["validation_success", "validation_failed"]
}
```

### List Active Workflow Instances

Retrieves a list of all currently active workflow instances with filtering and pagination support.

**Endpoint:** `GET /api/v1/workflows/instances`

**Request:**
```http
GET /api/v1/workflows/instances?status=running&workflow_id=workflow_67890&page=1&limit=10
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

**Response:**
```http
HTTP/1.1 200 OK
Content-Type: application/json

{
  "instances": [
    {
      "instance_id": "instance_abc123",
      "workflow_id": "workflow_67890",
      "workflow_name": "Order Processing Workflow",
      "instance_name": "Order #12345 Processing",
      "status": "running",
      "current_state": "validate_order",
      "created_at": "2025-09-20T16:00:00Z",
      "updated_at": "2025-09-20T16:00:03Z",
      "metadata": {
        "priority": "normal",
        "source": "web_order",
        "correlation_id": "corr-12345-6789"
      }
    },
    {
      "instance_id": "instance_def456",
      "workflow_id": "workflow_67890",
      "workflow_name": "Order Processing Workflow",
      "instance_name": "Order #12346 Processing",
      "status": "running",
      "current_state": "process_payment",
      "created_at": "2025-09-20T15:30:00Z",
      "updated_at": "2025-09-20T15:35:00Z",
      "metadata": {
        "priority": "high",
        "source": "mobile_app",
        "correlation_id": "corr-12346-7890"
      }
    }
  ],
  "pagination": {
    "page": 1,
    "limit": 10,
    "total_count": 2,
    "total_pages": 1
  },
  "filters_applied": {
    "status": "running",
    "workflow_id": "workflow_67890"
  }
}
```

## Error Responses

All endpoints may return the following error responses:

**401 Unauthorized:**
```http
HTTP/1.1 401 Unauthorized
Content-Type: application/json

{
  "error": "unauthorized",
  "message": "Invalid or expired authentication token",
  "code": "AUTH_001"
}
```

**403 Forbidden:**
```http
HTTP/1.1 403 Forbidden
Content-Type: application/json

{
  "error": "forbidden",
  "message": "Insufficient permissions to perform this action",
  "code": "AUTH_002"
}
```

**404 Not Found:**
```http
HTTP/1.1 404 Not Found
Content-Type: application/json

{
  "error": "not_found",
  "message": "Workflow or instance not found",
  "code": "RESOURCE_001"
}
```

**422 Validation Error:**
```http
HTTP/1.1 422 Unprocessable Entity
Content-Type: application/json

{
  "error": "validation_error",
  "message": "Request validation failed",
  "code": "VALIDATION_001",
  "details": [
    {
      "field": "name",
      "message": "Workflow name is required"
    },
    {
      "field": "states",
      "message": "At least one initial state is required"
    }
  ]
}
```




